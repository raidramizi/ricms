<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\SubmissionApproval;
use App\Notifications\ApplicationStatusUpdated;


class AdminSubmissionController extends Controller
{
    /* admin dashboard */
    public function index(Request $request)
    {
        $query = Submission::with([
            'user',
            'category',
            'type',
            'completion',
        ]);

        /* filter */
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('type_id')) {
            $query->where('type_id', $request->type_id);
        }
        if ($request->filled('status')) {
        $query->where('status', $request->status);
}

        /* table pagijn */
        $submissions = $query->latest()
            ->paginate(10)
            ->withQueryString();

        /* kpi query */
        $kpiQuery = Submission::query();

        if ($request->filled('search')) {
            $kpiQuery->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category_id')) {
            $kpiQuery->where('category_id', $request->category_id);
        }

        if ($request->filled('type_id')) {
            $kpiQuery->where('type_id', $request->type_id);
        }
        if ($request->filled('status')) {
        $kpiQuery->where('status', $request->status);
}

        /* kpi countd*/
        $pendingCount = (clone $kpiQuery)
            ->where('status', 'pending_admin')
            ->count();

        $verifiedCount = (clone $kpiQuery)
            ->where('status', 'verified_admin')
            ->count();

        $underHeadReviewCount = (clone $kpiQuery)
            ->where('status', 'sent_to_head')
            ->count();

        $recommendedCount = (clone $kpiQuery)
            ->where('status', 'approved_head')
            ->count();

        $approvedCount = (clone $kpiQuery)
            ->where('status', 'approved')
            ->count();

        $rejectedCount = (clone $kpiQuery)
            ->whereIn('status', ['rejected_admin', 'rejected_head'])
            ->count();

        $totalCount = (clone $kpiQuery)->count();

        /*  DEFAULT GRAPH (12 MONTHS) ================= */
        $chartLabels = collect(range(1, 12))->map(fn($m) =>
            Carbon::create()->month($m)->format('M')
        );

        $pendingSeries = [];
        $verifiedSeries = [];
        $underHeadSeries = [];
        $rejectedSeries = [];
        $recommendedSeries = [];
        $approvedSeries = [];


        foreach (range(1, 12) as $month) {

            $pendingSeries[] = Submission::whereMonth('updated_at', $month)
    ->where('status', 'pending_admin')
    ->count();

$verifiedSeries[] = Submission::whereMonth('updated_at', $month)
    ->where('status', 'verified_admin')
    ->count();

$underHeadSeries[] = Submission::whereMonth('updated_at', $month)
    ->where('status', 'sent_to_head')
    ->count();

$rejectedSeries[] = Submission::whereMonth('updated_at', $month)
    ->whereIn('status', ['rejected_admin', 'rejected_head'])
    ->count();

$recommendedSeries[] = Submission::whereMonth('updated_at', $month)
    ->where('status', 'approved_head')
    ->count();

$approvedSeries[] = Submission::whereMonth('updated_at', $month)
    ->where('status', 'approved')
    ->count();
        }

        return view('admin.submissions.index', compact(
    'submissions',
    'pendingCount',
    'verifiedCount',
    'underHeadReviewCount',
    'rejectedCount',
    'recommendedCount',
    'approvedCount',
    'totalCount',
    'pendingSeries',
    'verifiedSeries',
    'underHeadSeries',
    'rejectedSeries',
    'recommendedSeries',
    'approvedSeries',
    'chartLabels'

        ));
    }

    /*
        GRAPH API (FULL FIXED)
   */
public function graphData(Request $request)
{
    $query = Submission::query();


    // DATE RANGE

    $from = $request->filled('from')
        ? Carbon::parse($request->from)->startOfDay()
        : Carbon::now()->startOfMonth()->startOfDay();

    $to = $request->filled('to')
        ? Carbon::parse($request->to)->endOfDay()
        : Carbon::now()->endOfDay();

    $query->whereBetween('created_at', [$from, $to]);


    // OPTIONAL FILTERS

    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    if ($request->filled('type_id')) {
        $query->where('type_id', $request->type_id);
    }

    $statusFilter = $request->status; // ✅ IMPORTANT

    // PERIOD

    $period = Carbon::parse($from)->daysUntil($to);

    $labels = [];

    $pending = [];
    $verified = [];
    $underHead = [];
    $rejected = [];
    $recommended = [];
    $approved = [];

    // LOOP DAILY DATA

    foreach ($period as $date) {

        $day = $date->format('Y-m-d');
        $labels[] = $date->format('d M');

        // base query per day
        $base = (clone $query)->whereDate('updated_at', $day);

        //  APPLY STATUS FILTER HERE (THIS IS THE FIX)
        if ($statusFilter) {
            $base->where('status', $statusFilter);
        }


        $pending[] = (clone $base)->where('status', 'pending_admin')->count();

        $verified[] = (clone $base)->where('status', 'verified_admin')->count();

        $underHead[] = (clone $base)->where('status', 'sent_to_head')->count();

        $recommended[] = (clone $base)->where('status', 'approved_head')->count();

        $approved[] = (clone $base)->where('status', 'approved')->count();

        $rejected[] = (clone $base)
            ->whereIn('status', ['rejected_admin', 'rejected_head'])
            ->count();
    }




    return response()->json([
        'labels' => $labels,
        'pending' => $pending,
        'verified' => $verified,
        'underHead' => $underHead,
        'recommended' => $recommended,
        'approved' => $approved,
        'rejected' => $rejected,
    ]);
}
    /*
    submit to head
     */
   public function submitToHead($id)
{
    $submission = Submission::findOrFail($id);

    if ($submission->status !== 'verified_admin') {
        return back()->with('error', 'Only verified submissions can be sent to the Head.');
    }

    $submission->update([
        'status' => 'sent_to_head',
        'sent_to_head_at' => now(),
    ]);
    $submission->user->notify(new ApplicationStatusUpdated($submission));

    return back()->with('success', 'Submission successfully forwarded to Head.');
}


    public function processFiles(Request $request, $id)
{
    $submission = Submission::findOrFail($id);

    $action = $request->input('action');

    if (!in_array($action, ['approve', 'reject'])) {
        return back()->with('error', 'Invalid action selected.');
    }

    if ($action === 'reject') {

        $request->validate([
            'admin_comment' => 'required|string|max:1000',
        ]);

        $submission->update([
            'status' => 'rejected_admin',
            'rejected_at' => now(),
            'admin_comment' => $request->input('admin_comment'),
        ]);


        $submission->user->notify(new ApplicationStatusUpdated($submission));

    } else {

        $submission->update([
            'status' => 'verified_admin',
            'verified_at' => now(),
            'admin_comment' => $request->input('admin_comment'),
        ]);


        $submission->user->notify(new ApplicationStatusUpdated($submission));
    }

    return back()->with('success', 'Submission updated successfully.');
}
    public function recommended(Request $request)
{
    $query = Submission::with([
        'user',
        'type',
        'category',
        'completion',
        'approval'
    ])
    ->where('status', 'approved_head');

    if ($request->filled('search')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('type_id')) {
        $query->where('type_id', $request->type_id);
    }

    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    $submissions = $query->latest()
        ->paginate(10)
        ->withQueryString();

    return view('admin.recommendedapplication', compact('submissions'));
}
public function uploadProof(Request $request, $id)
{
    $request->validate([
        'proof_file' => 'required|file|mimes:pdf,doc,docx|max:2048'
    ]);

    $submission = Submission::findOrFail($id);

    $file = $request->file('proof_file');
    $path = $file->store('proof_files', 'public');

    SubmissionApproval::updateOrCreate(
        ['submission_id' => $id],
        [
            'proof_file' => $path,
            'proof_name' => $file->getClientOriginalName(),
            'approved_by' => Auth::id()
        ]
    );

    return back()->with('success', 'Proof uploaded successfully.');
}
public function markDone($id)
{
    $submission = Submission::findOrFail($id);

    // 1. ensure proof exists
    if (!$submission->approval || !$submission->approval->proof_file) {
        return back()->with('error', 'Proof file required before marking done.');
    }

    // 2. mark completion
    $submission->completion()->create([
        'done_at' => now(),
    ]);

    // 3. FINAL STATUS CHANGE
    $submission->status = 'approved'; // or 'completed'
    $submission->save();
    $submission->user->notify(new ApplicationStatusUpdated($submission));

    return back()->with('success', 'Marked as approved successfully.');
}

    public function downloadReport(Request $request)
    {
        $submissions = Submission::with(['user','type','category'])
            ->when($request->search, fn($q) =>
                $q->whereHas('user', fn($u) =>
                    $u->where('name','like','%'.$request->search.'%')
                )
            )
            ->when($request->category_id, fn($q) =>
                $q->where('category_id', $request->category_id)
            )
            ->when($request->type_id, fn($q) =>
                $q->where('type_id', $request->type_id)
            )
            ->get();

        $pendingCount = $submissions->where('status','pending_admin')->count();
        $verifiedCount = $submissions->where('status','verified_admin')->count();
        $underHeadReviewCount = $submissions->where('status','sent_to_head')->count();
        $rejectedCount = $submissions->whereIn('status',['rejected_admin','rejected_head'])->count();
        $recommendedCount = $submissions->where('status','approved_head')->count();
        $approvedCount = $submissions->where('status','approved')->count();
        $totalCount = $submissions->count();

        $analysis = "Total: {$totalCount}. Pending: {$pendingCount}. Verified: {$verifiedCount}. "
    . "Under Head Review: {$underHeadReviewCount}. Rejected: {$rejectedCount}. "
    . "Recommended: {$recommendedCount}. Approved: {$approvedCount}.";

        $pdf = Pdf::loadView('admin.submissions.report', compact(
    'submissions',
    'pendingCount',
    'verifiedCount',
    'underHeadReviewCount',
    'rejectedCount',
    'recommendedCount',
    'approvedCount',
    'totalCount',
    'analysis'
));

        return $pdf->download('R&I-Report-' . now()->format('Y-m-d') . '.pdf');
    }

    /*
    save chart
     */
    public function saveChart(Request $request)
    {
        $image = str_replace('data:image/png;base64,', '', $request->image);
        $image = str_replace(' ', '+', $image);

        Storage::disk('public')->put('chart.png', base64_decode($image));

        return response()->json(['success' => true]);
    }
    public function history(Request $request)
{
    $query = Submission::with([
        'user',
        'type',
        'category',
        'approval',
        'completion'
    ])
    ->whereHas('completion');

    // ================= SEARCH =================
    if ($request->filled('search')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });
    }

    // ================= CATEGORY =================
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    // ================= TYPE =================
    if ($request->filled('type_id')) {
        $query->where('type_id', $request->type_id);
    }

    $submissions = $query->latest()
        ->paginate(10)
        ->withQueryString();

    return view('admin.history', compact('submissions'));
}
}
