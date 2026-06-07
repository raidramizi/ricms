<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'form_file' => 'required|file|mimes:pdf,doc,docx',
            'evidence.*' => 'nullable|file',
            'evidence_optional.*' => 'nullable|file',
            'type_id' => 'nullable|integer',
            'category_id' => 'nullable|integer',
        ]);

        $user = Auth::user();

        if (!$user) {
            return back()->with('error', 'User not authenticated');
        }

        $staffId = $user->staff_id ?? 'unknown';
        $userName = $user->name ?? 'unknown';


        $formFile = $request->file('form_file');
        $formPath = $formFile->store("forms/{$staffId}", 'public');


        $evidenceFiles = [];

        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                if ($file && $file->isValid()) {

                    $path = $file->store("evidence/{$staffId}", 'public');

                    $evidenceFiles[] = [
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ];
                }
            }
        }


        $optionalFiles = [];

        if ($request->hasFile('evidence_optional')) {
            foreach ($request->file('evidence_optional') as $file) {
                if ($file && $file->isValid()) {

                    $path = $file->store("evidence_optional/{$staffId}", 'public');

                    $optionalFiles[] = [
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ];
                }
            }
        }

        /*
        SAVE SUBMISSION
        */
        Submission::create([
            'staff_id' => $staffId,
            'user_name' => $userName,
            'type_id' => $request->type_id,
            'category_id' => $request->category_id,

            'form_file' => $formPath,
            'form_file_name' => $formFile->getClientOriginalName(),

            'evidence_files' => $evidenceFiles,
            'evidence_optional' => $optionalFiles,

            'status' => 'pending_admin',
        ]);

        return redirect()->route('claim.status')
            ->with('success', 'Submission successful!');
    }

    /*
      STATUS PAGE
     */
    public function status(Request $request)
    {
        $staffId = Auth::user()?->staff_id;

        $query = Submission::with(['category', 'type'])
            ->where('staff_id', $staffId);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('type_id')) {
            $query->where('type_id', $request->type_id);
        }

        $data = $query->latest()->paginate(5)->appends($request->query());

        return view('claim.status', compact('data'));
    }
}
