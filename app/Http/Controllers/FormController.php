<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Type;
use App\Models\Form;
use Illuminate\Http\Request;
use App\Models\DocumentRequirement;
use Illuminate\Support\Facades\Storage;

class FormController extends Controller
{

    public function index()
{
    $forms = Form::with(['category', 'type'])->get();
    $categories = Category::all();
    $types = Type::all();
    $documents = DocumentRequirement::with(['category', 'type'])->get();

    return view('admin.Formsedit', compact(
        'forms',
        'categories',
        'types',
        'documents'
    ));
}


    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'file' => 'required|file|mimes:pdf,doc,docx',


        'category_id' => 'required|integer|exists:categories,id',
        'type_id' => 'required|integer|exists:types,id',

        'label' => 'nullable|string|max:255',
    ]);

    $file = $request->file('file');
    $path = $file->store('forms', 'public');

    Form::create([
        'name' => $request->name,
        'file_path' => $path,
        'original_name' => $file->getClientOriginalName(),
        'category_id' => $request->category_id,
        'type_id' => $request->type_id,
        'label' => $request->label,
    ]);

    return back()->with('success', 'Form uploaded successfully!');
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx',
        ]);

        $form = Form::findOrFail($id);

        // Delete old file
        if ($form->file_path && Storage::disk('public')->exists($form->file_path)) {
            Storage::disk('public')->delete($form->file_path);
        }

        $file = $request->file('file');

        // Store new file
        $path = $file->store('forms', 'public');

        // Update record
        $form->update([
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(), // keep original name updated
        ]);

        return back()->with('success', 'Form replaced successfully!');
    }

    /**
     * Delete form (Admin delete)
     */
    public function destroy($id)
    {
        $form = Form::findOrFail($id);

        // Delete file from storage
        if ($form->file_path && Storage::disk('public')->exists($form->file_path)) {
            Storage::disk('public')->delete($form->file_path);
        }

        $form->delete();

        return back()->with('success', 'Form deleted!');
    }

    /**
     * Download file with correct original filename
     */
    public function download($id)
{
    $form = Form::findOrFail($id);

    $filePath = storage_path('app/public/' . $form->file_path);

    if (!file_exists($filePath)) {
        abort(404, 'File not found');
    }

    // FORCE correct filename
    $filename = $form->original_name;

    // fallback safety (if DB is broken)
    if (!$filename || !str_contains($filename, '.')) {
        $filename = basename($form->file_path);
    }

    return response()->download($filePath, $filename);
}
}
