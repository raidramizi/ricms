<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\HeadSubmissionEdit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ApplicationStatusUpdated;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class HeadSubmissionController extends Controller
{

   public function index(Request $request)
{
    /*
        BASE QUERY (WITH FILTERS)
      */
    $baseQuery = Submission::with(['user', 'type', 'category', 'fileEdits'])
        ->whereIn('status', ['sent_to_head', 'approved_head', 'rejected_head']);

    // SEARCH
    if ($request->filled('search')) {
        $baseQuery->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });
    }

    // CATEGORY
    if ($request->filled('category_id')) {
        $baseQuery->where('category_id', $request->category_id);
    }

    // TYPE
    if ($request->filled('type_id')) {
        $baseQuery->where('type_id', $request->type_id);
    }

    // STATUS
    if ($request->filled('status')) {
        $baseQuery->where('status', $request->status);
    }


    $submissions = (clone $baseQuery)
        ->latest()
        ->paginate(10)
        ->withQueryString();


    $pendingHead = (clone $baseQuery)->where('status', 'sent_to_head')->count();
    $recommended = (clone $baseQuery)->where('status', 'approved_head')->count();
    $rejected = (clone $baseQuery)->where('status', 'rejected_head')->count();
    $total = (clone $baseQuery)->count();


    $chartLabels = collect(range(1, 12))->map(fn($m) =>
        Carbon::create()->month($m)->format('M')
    );

    $pendingSeries = [];
    $recommendedSeries = [];
    $rejectedSeries = [];

    foreach (range(1, 12) as $month) {

        $pendingSeries[] = (clone $baseQuery)
            ->whereMonth('updated_at', $month)
            ->where('status', 'sent_to_head')
            ->count();

        $recommendedSeries[] = (clone $baseQuery)
            ->whereMonth('updated_at', $month)
            ->where('status', 'approved_head')
            ->count();

        $rejectedSeries[] = (clone $baseQuery)
            ->whereMonth('updated_at', $month)
            ->where('status', 'rejected_head')
            ->count();
    }

    return view('head.dashboardreview', compact(
        'submissions',
        'pendingHead',
        'recommended',
        'rejected',
        'total',
        'chartLabels',
        'pendingSeries',
        'recommendedSeries',
        'rejectedSeries'
    ));
}

    public function approve(Request $request, $id)
    {
        $request->validate([

            'public_comment' => 'nullable|string|max:1000',
            'internal_comment' => 'nullable|string|max:1000',
        ]);

        $submission = Submission::findOrFail($id);

        if ($submission->status !== 'sent_to_head') {
            return back()->with('error', 'Already processed.');
        }

        if ($request->hasFile('signed_file')) {
            $submission->signed_file = $request->file('signed_file')
                ->store('signed_documents', 'public');
        }

        $submission->update([
            'status' => 'approved_head',
            'head_reviewed_at' => now(),
            'head_public_comment' => $request->public_comment,
            'head_internal_comment' => $request->internal_comment,
        ]);
        $submission->user->notify(new ApplicationStatusUpdated($submission));

        return back()->with('success', 'Recommended successfully.');
    }


    public function reject(Request $request, $id)
    {
        $request->validate([
            'public_comment' => 'required|string|max:1000',
            'internal_comment' => 'nullable|string|max:1000',
        ]);

        $submission = Submission::findOrFail($id);

        $submission->update([
            'status' => 'rejected_head',
            'head_public_comment' => $request->public_comment,
            'head_internal_comment' => $request->internal_comment,
            'head_reviewed_at' => now(),
        ]);
        $submission->user->notify(new ApplicationStatusUpdated($submission));

        return back()->with('success', 'Rejected successfully.');
    }


    public function updateDocuments(Request $request, $id)
    {
        $submission = Submission::findOrFail($id);

        /* MAIN FILE */
        if ($request->hasFile('replace_main_form')) {
            $file = $request->file('replace_main_form');
            $path = $file->store('head-edited/main', 'public');

            HeadSubmissionEdit::create([
                'submission_id' => $submission->id,
                'head_id' => Auth::id(),
                'file_type' => 'main_form',
                'file_key' => 'main_form',
                'old_file' => $submission->form_file,
                'new_file' => $path,
            ]);

            $submission->form_file = $path;
            $submission->form_file_name = $file->getClientOriginalName();
        }

        /* EVIDENCE */
        $evidence = is_string($submission->evidence_files)
            ? json_decode($submission->evidence_files, true)
            : $submission->evidence_files;

        if ($request->hasFile('replace_evidence')) {
            foreach ($request->file('replace_evidence') as $key => $file) {
                $path = $file->store('head-edited/evidence', 'public');

                $evidence[$key] = [
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ];
            }

            $submission->evidence_files = json_encode($evidence);
        }

        /* OPTIONAL */
        $optional = is_string($submission->evidence_optional)
            ? json_decode($submission->evidence_optional, true)
            : $submission->evidence_optional;

        if ($request->hasFile('replace_optional')) {
            foreach ($request->file('replace_optional') as $key => $file) {
                $path = $file->store('head-edited/optional', 'public');

                $optional[$key] = [
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ];
            }

            $submission->evidence_optional = json_encode($optional);
        }

        $submission->save();

        return back()->with('success', 'Documents updated successfully.');
    }

public function downloadReport(Request $request)
{
    /* =========================================================
        SAME FILTER LOGIC
    ========================================================= */
    $baseQuery = Submission::with(['user', 'type', 'category'])
        ->whereIn('status', ['sent_to_head', 'approved_head', 'rejected_head']);

    if ($request->filled('search')) {
        $baseQuery->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('category_id')) {
        $baseQuery->where('category_id', $request->category_id);
    }

    if ($request->filled('type_id')) {
        $baseQuery->where('type_id', $request->type_id);
    }

    if ($request->filled('status')) {
        $baseQuery->where('status', $request->status);
    }

    $submissions = (clone $baseQuery)->get();

    /* ===== COUNTS (FILTERED) ===== */
    $pending = (clone $baseQuery)->where('status', 'sent_to_head')->count();
    $recommended = (clone $baseQuery)->where('status', 'approved_head')->count();
    $rejected = (clone $baseQuery)->where('status', 'rejected_head')->count();
    $total = (clone $baseQuery)->count();

    $generatedAt = now()->format('d M Y, h:i A');

    /* ===== CHART CACHE ===== */
    $statusChart = Cache::get('head_status_chart');
    $trendChart  = Cache::get('head_trend_chart');

    if (is_array($statusChart)) {
        $statusChart = $statusChart['data'] ?? null;
    }

    if (!$statusChart || !str_contains($statusChart, 'base64')) {
        $statusChart = null;
    }

    if (is_array($trendChart)) {
        $trendChart = $trendChart['data'] ?? null;
    }

    if (!$trendChart || !str_contains($trendChart, 'base64')) {
        $trendChart = null;
    }

    $pdf = Pdf::loadView('head.report', [
        'submissions' => $submissions,
        'pending' => $pending,
        'recommended' => $recommended,
        'rejected' => $rejected,
        'total' => $total,
        'generatedAt' => $generatedAt,
        'statusChart' => $statusChart,
        'trendChart' => $trendChart,
    ])->setPaper('a4', 'landscape');

    return $pdf->download('Head_Report_' . now()->format('Ymd') . '.pdf');
}
     public function save(Request $request)
{
    $request->validate([
        'statusImage' => 'required',
        'trendImage' => 'required',
    ]);

    Cache::put('head_status_chart', $request->statusImage, now()->addMinutes(10));
    Cache::put('head_trend_chart', $request->trendImage, now()->addMinutes(10));

    return response()->json(['success' => true]);
}

    public function recommended(Request $request)
    {
        return $this->index($request);
    }
}
