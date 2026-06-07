<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequirement;
use Illuminate\Http\Request;

class DocumentRequirementController extends Controller
{
    public function index()
    {
        $documents = DocumentRequirement::with(['category', 'type'])->get();
        return view('admin.document-requirements', compact('documents'));
    }

   public function store(Request $request)
{
    $request->validate([
        'label' => 'required|string|max:255',
        'input_name' => 'required|string|max:255',

        //  STRICT VALIDATION (IMPORTANT FIX)
        'category_id' => 'required|exists:categories,id',
        'type_id' => 'required|exists:types,id',

        'section' => 'required|string',
        'is_required' => 'nullable|boolean',
    ]);

    DocumentRequirement::create([
        'label' => $request->label,
        'input_name' => $request->input_name,
        'category_id' => $request->category_id,
        'type_id' => $request->type_id,
        'section' => $request->section,
        'is_required' => $request->is_required ?? 0,
    ]);

    return back()->with('success', 'Document added!');
}

    public function destroy($id)
    {
        DocumentRequirement::findOrFail($id)->delete();
        return back()->with('success', 'Deleted!');
    }

    public function toggle($id)
    {
        $doc = DocumentRequirement::findOrFail($id);
        $doc->is_required = !$doc->is_required;
        $doc->save();

        return back()->with('success', 'Updated!');
    }
}
