<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Models\Submission;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\AdminSubmissionController;
use App\Http\Controllers\HeadSubmissionController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\DocumentRequirementController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/


Route::get('/', fn () => view('welcome'))-> name('welcome');

/*
|--------------------------------------------------------------------------
| AUTH USER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'active'])->group(function () {
   Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    /* HOME */
    Route::get('/home', fn () => view('home'))->name('home');

    /* CLAIM PAGE */
    Route::get('/claim', fn () => view('claim'))->name('claim');

    /* SUBMISSION */
    Route::post('/submit', [SubmissionController::class, 'store'])->name('submit.form');

    /* STATUS */
    Route::get('/claim-status', [SubmissionController::class, 'status'])->name('claim.status');

    /* MY SUBMISSIONS */
    Route::get('/my-submissions', function () {
        $user = Auth::user();

        $data = Submission::where('staff_id', $user?->staff_id)
            ->latest()
            ->get();

        return view('submissions', compact('data'));
    })->name('my.submissions');

    /* SECURE FILE DOWNLOAD */
   Route::get('/download-file', function (\Illuminate\Http\Request $request) {

    $path = $request->query('path');
    $name = $request->query('name');

    /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
    $disk = \Illuminate\Support\Facades\Storage::disk('public');

    if (!$path || !$disk->exists($path)) {
        abort(404, "File not found.");
    }

    return $disk->download($path, $name);

     })->name('secure.download');

     //forms
   Route::get('/form/download/{id}', function ($id) {

    $form = \App\Models\Form::findOrFail($id);

    /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
    $disk = Storage::disk('public');

    if (!$form->file_path || !$disk->exists($form->file_path)) {
        abort(404, 'Form not found.');
    }

    return $disk->download(
        $form->file_path,

        // ✅ FIX: use original name OR force extension
        $form->original_name ?? basename($form->file_path)
    );

})->name('form.download');

    /* HANDBOOK */
    Route::get('/handbook', fn () =>
        response()->file(public_path('files/handbook.pdf'))
    )->name('handbook');

    /* ============================= */
    /* CONFERENCE */
    /* ============================= */
    Route::prefix('conference')->group(function () {
        Route::get('/', fn () => view('conference.conference'))->name('conference');
        Route::get('/local', [ApplicationController::class, 'conferenceLocal'])->name('conference.local');
        Route::get('/overseas', [ApplicationController::class, 'conferenceOverseas'])->name('conference.overseas');

    });

    /* ============================= */
    /* PUBLICATION */
    /* ============================= */
    Route::prefix('publication')->group(function () {
         Route::get('/funding', [ApplicationController::class, 'publicationFunding'])->name('publication.funding');
         Route::get('/reward', [ApplicationController::class, 'publicationReward'])->name('publication.reward');

    });

    /* ============================= */
    /* GRANT */
    /* ============================= */
    Route::prefix('grant')->group(function () {
         Route::get('/general', [ApplicationController::class, 'grantGeneral'])->name('grant.general');
         Route::get('/purchase', [ApplicationController::class, 'grantPurchase'])->name('grant.purchase');
         Route::get('/graduate', [ApplicationController::class, 'grantGraduate'])->name('grant.graduate');
        Route::get('/virement', [ApplicationController::class, 'virement'])->name('grant.virement');
    });

    /* ============================= */
    /* PROFILE */
    /* ============================= */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* ============================= */
    /*  ADMIN ROUTES */
    /* ============================= */
    Route::prefix('admin')->name('admin.')->group(function () {

        /* DASHBOARD */
        Route::get('/submissions', [AdminSubmissionController::class, 'index'])
            ->name('submissions.index');
        Route::get('/submissions/report', [AdminSubmissionController::class, 'downloadReport'])
        ->name('submissions.report');

        /* ACTIONS */
        Route::post('/submissions/{id}/approve', [AdminSubmissionController::class, 'approve'])
            ->name('submissions.approve');

        Route::post('/submissions/{id}/reject', [AdminSubmissionController::class, 'reject'])
            ->name('submissions.reject');

        Route::post('/submissions/{id}/comment', [AdminSubmissionController::class, 'saveComment'])
            ->name('submissions.comment');

        Route::post('/submissions/{id}/process-files', [AdminSubmissionController::class, 'processFiles'])
            ->name('submissions.processFiles');

        Route::post('/submissions/{id}/submit-head', [AdminSubmissionController::class, 'submitToHead'])
            ->name('submissions.submitHead');

        Route::get('/recommended-applications', [AdminSubmissionController::class, 'recommended'])
            ->name('submissions.recommended');


        Route::patch('/submission/{id}/done', [AdminSubmissionController::class, 'markDone'])
            ->name('submission.markDone');

        Route::get('/submissions/graph', [AdminSubmissionController::class, 'graphData'])
            ->name('submissions.graph');

        Route::post('/chart/save', [AdminSubmissionController::class, 'saveChart'])  ->name('chart.save');

        Route::post('/submissions/{id}/upload-proof', [AdminSubmissionController::class, 'uploadProof']) ->name('uploadProof');
        Route::get('/history', [AdminSubmissionController::class, 'history'])->name('submissions.history');


        /* ============================= */
        /*  FORM MANAGEMENT (NEW) */
        /* ============================= */

        // Page
        Route::get('/forms', [FormController::class, 'index'])
            ->name('forms.index');

        // Upload
        Route::post('/forms', [FormController::class, 'store'])
            ->name('forms.store');

        // Replace
        Route::post('/forms/{id}', [FormController::class, 'update'])
            ->name('forms.update');

        // Delete
        Route::delete('/forms/{id}', [FormController::class, 'destroy'])
            ->name('forms.destroy');

                /* ============================= */
        /*  DOCUMENT REQUIREMENTS */
        /* ============================= */

        // Store new rule
        Route::post('/document-requirements', [DocumentRequirementController::class, 'store'])
            ->name('documents.store');

        // Delete rule
        Route::delete('/document-requirements/{id}', [DocumentRequirementController::class, 'destroy'])
            ->name('documents.destroy');

        // Toggle required (optional but useful)
        Route::post('/document-requirements/{id}/toggle', [DocumentRequirementController::class, 'toggle'])
            ->name('documents.toggle');
    });

    /* ============================= */
    /* HEAD ROUTES */
    /* ============================= */
Route::prefix('head')->name('head.')->group(function () {


    Route::post('/submissions/{id}/approve',
        [HeadSubmissionController::class, 'approve'])
        ->name('submissions.approve');

    Route::post('/submissions/{id}/reject',
        [HeadSubmissionController::class, 'reject'])
        ->name('submissions.reject');

    Route::post('/submissions/{id}/check-docs',
        [HeadSubmissionController::class, 'saveChecks'])
        ->name('submission.check.docs');

    Route::get('/submissions/report',
        [HeadSubmissionController::class, 'downloadReport'])
        ->name('submissions.report');


    Route::post('/submissions/{id}/files/edit',
        [HeadSubmissionController::class, 'editFile'])
        ->name('files.edit.store');

    Route::post('/submissions/{id}/update-documents',
        [HeadSubmissionController::class, 'updateDocuments'])
        ->name('submissions.updateDocuments');

    Route::get('/dashboard-review',
    [HeadSubmissionController::class, 'index'])
    ->name('dashboardreview');

    Route::post('chart/save', [HeadSubmissionController::class, 'save'])
    ->name('chart.save');

    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    Route::post('/users/update/{id}', [UserController::class, 'update'])
        ->name('users.update');

});
    /* SWITCH ROLE */
    Route::get('/switch-role/{role}', function ($role) {
        if ($role === 'reset') {
            session()->forget('role_override');
        } else {
            session(['role_override' => $role]);
        }
        return back();
    })->name('switch.role');
});



/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
