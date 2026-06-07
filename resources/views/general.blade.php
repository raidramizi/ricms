<x-app-layout>
    <x-slot name="title">
    Grant General
</x-slot>

    <x-slot name="header">
         <div class="flex items-center gap-3">
            <button onclick="window.history.back()"
                class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-lg transition text-sm text-gray-700">
                < Back
            </button>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('General Grant Application') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div style="max-width:800px; margin:0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">

            <h3 style="margin-bottom:20px; font-size: 1.5rem; font-weight: 700; color: #1a202c;">
                General Grant Application Form
            </h3>


       {{-- DOWNLOAD FORMS --}}
        @if($forms->count())
        <div style="margin-bottom:25px; padding:15px; background:#f8fafc; border-radius:8px; border:1px dashed #cbd5e0;">
            <p style="margin-bottom:10px; font-size:0.9rem;">
                Download the relevant forms before uploading.
            </p>

            @foreach($forms as $form)
                <a href="{{ route('form.download', $form->id) }}" class="btn">
                    Download {{ $form->label ?? $form->name }}
                </a>
            @endforeach
        </div>
        @endif

        {{-- SUCCESS --}}
        @if(session('success'))
            <div class="success-box">{{ session('success') }}</div>
        @endif

        {{-- ERRORS --}}
        @if($errors->any())
            <div class="error-box">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('submit.form') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="category_id" value="{{ $categoryId }}">
            <input type="hidden" name="type_id" value="{{ $typeId }}">
{{-- PRIMARY FILE UPLOAD --}}

            {{-- ================= MAIN ================= --}}
            @if(isset($documents['main']))
                <h4 class="section-title main-title">Main Form</h4>

                <table class="doc-table main-table">
                    <thead>
                        <tr>
                            <th>Document</th>
                            <th>Upload</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($documents['main'] as $doc)
                            <tr>
                                <td>
                                    {{ $doc->label }}
                                    @if($doc->is_required)
                                        <span class="req">*</span>
                                    @endif
                                </td>

                                <td>
                                    <input type="file"
                                        name="{{ $doc->input_name }}"
                                        @if($doc->is_required) required @endif>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- ================= EVIDENCE ================= --}}
            @if(isset($documents['evidence']))
                <h4 class="section-title evidence-title">Evidence Documents</h4>

                <table class="doc-table evidence-table">
                    <thead>
                        <tr>
                            <th>Document</th>
                            <th>Upload</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($documents['evidence'] as $doc)
                            <tr>
                                <td>
                                    {{ $doc->label }}
                                    @if($doc->is_required)
                                        <span class="req">*</span>
                                    @endif
                                </td>

                                <td>
                                    <input type="file"
                                        name="evidence[{{ $doc->input_name }}]"
                                        @if($doc->is_required) required @endif>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- ================= OPTIONAL ================= --}}
            <h4 class="section-title optional-title">Other Supporting Documents (Optional)</h4>

            <table class="doc-table optional-table">
                <thead>
                    <tr>
                        <th>Document</th>
                        <th>Upload</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>Additional Files</td>
                        <td>
                            <input type="file" name="evidence_optional[]" multiple>
                        </td>
                    </tr>
                </tbody>
            </table>


                <p style="font-size: 0.85rem; color: #718096; margin-bottom: 15px;">
                    <span style="color: #e53e3e; font-weight: bold;">*</span> indicates a required field.
                </p>


            {{-- SUBMIT --}}
            <div style="text-align:right; margin-top:30px;">
                <button type="submit" class="submit-btn">
                    Submit Application
                </button>
            </div>

        </form>
    </div>
</div>

<style>
.btn {
    display:inline-block;
    padding:10px 20px;
    background:#3182ce;
    color:white;
    border-radius:6px;
    font-size:14px;
    margin-right:8px;
    text-decoration:none;
}

.submit-btn {
    background:#38b2ac;
    color:white;
    padding:12px 25px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-weight:600;
}

.section-title {
    margin:20px 0 10px;
    font-weight:bold;
}

/* ================= SECTION COLORS ================= */

/* MAIN (BLUE) */
.main-title {
    color:#1e40af;
    border-left:4px solid #1e40af;
    padding-left:10px;
}

/* EVIDENCE (PURPLE) */
.evidence-title {
    color:#4f46e5;
    border-left:4px solid #4f46e5;
    padding-left:10px;
}

/* OPTIONAL (GREEN) */
.optional-title {
    color:#16a34a;
    border-left:4px solid #16a34a;
    padding-left:10px;
}

/* REQUIRED STAR */
.req {
    color:red;
    font-weight:bold;
    margin-left:4px;
}

/* ================= TABLE BASE ================= */
.doc-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 25px;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    table-layout: fixed;
}

.doc-table th {
    color: white;
    text-align: left;
    padding: 12px;
    font-size: 0.9rem;
}

/* ================= TABLE HEADER COLORS ================= */

/* MAIN TABLE */
.main-table th {
    background: linear-gradient(135deg, #1e40af, #3b82f6);
}

/* EVIDENCE TABLE */
.evidence-table th {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
}

/* OPTIONAL TABLE */
.optional-table th {
    background: linear-gradient(135deg, #16a34a, #22c55e);
}

/* TABLE CELLS */
.doc-table td {
    padding: 12px;
    border-bottom: 1px solid #edf2f7;
    vertical-align: middle;
}

/* COLUMN WIDTH */
.doc-table th:nth-child(1),
.doc-table td:nth-child(1) {
    width: 60%;
}

.doc-table th:nth-child(2),
.doc-table td:nth-child(2) {
    width: 40%;
}

/* FILE INPUT */
.doc-table input[type="file"] {
    width: 100%;
    display: block;
}

/* ROW HOVER */
.doc-table tr:hover {
    background: #f9fafb;
}

/* ALERTS */
.success-box {
    background:#f0fff4;
    color:#2f855a;
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
}

.error-box {
    background:#fff5f5;
    color:#c53030;
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
}
</style>

</x-app-layout>
