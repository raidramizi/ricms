<x-app-layout>
<x-slot name="title">
    Forms Management
</x-slot>
<style>
    /* =========================
       BASE BUTTON STYLE (MODERN)
    ========================= */
    .btn-base {
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        color: white;
        border: none;
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none;
        box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    }

    .btn-base:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    }

    .btn-base:active {
        transform: scale(0.96);
    }

    /* Buttons */
    .btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
    .btn-warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .btn-view { background: linear-gradient(135deg, #a3eb09, #64ad10); }
    .btn-danger { background: linear-gradient(135deg, #dc3545, #b91c1c); }

    .btn-danger:hover {
        filter: brightness(1.05);
    }

    /* Inputs */
    .form-input-custom {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 10px 12px;
        outline: none;
        transition: 0.2s;
        width: 100%;
        background: #fff;
    }

    .form-input-custom:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59,130,246,0.15);
    }

    /* Card */
    .card {
        background: white;
        border-radius: 14px;
        border: 1px solid #eef0f3;
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        transition: 0.25s;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 30px rgba(0,0,0,0.08);
    }

    /* Section */
    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .group-line {
        height: 1px;
        background: #e5e7eb;
        flex: 1;
    }

    .group-title {
        background: #f9fafb;
        padding: 5px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        color: #4b5563;
    }
</style>

<div class="p-6 max-w-6xl mx-auto">
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        📄 User Application Management
    </h2>
</x-slot>
<div class="flex gap-2 mb-6 border-b">
    <button onclick="showTab('forms')" id="tab-forms"
        class="px-4 py-2 font-semibold border-b-2 border-blue-600 text-blue-600">
       User Download Forms Section
    </button>

    <button onclick="showTab('docs')" id="tab-docs"
        class="px-4 py-2 font-semibold text-gray-500">
       User Upload Documents Section
    </button>
</div>
<div id="forms-section">


<!-- SUCCESS -->
@if(session('success'))
    <div class="mb-4 p-4 bg-green-50 text-green-700 border-l-4 border-green-500 rounded-lg shadow-sm">
        {{ session('success') }}
    </div>
@endif

<!-- UPLOAD FORM -->
<div class="card p-6 mb-8">

    <h3 class="text-lg font-bold mb-5 text-gray-700">
        ➕ Upload New Form
    </h3>

    <form method="POST" action="{{ route('admin.forms.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" name="name" class="form-input-custom" placeholder="Form Name" required>
            <input type="text" name="label" class="form-input-custom" placeholder="Label">
            <input type="file" name="file" class="form-input-custom" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
           <select name="category_id" id="cat_filter_forms" class="filter-input" required>
            <option value="">Select Category</option>
            <option value="1" {{ request('category_id') == 1 ? 'selected' : '' }}>Publication</option>
            <option value="2" {{ request('category_id') == 2 ? 'selected' : '' }}>Grant</option>
            <option value="3" {{ request('category_id') == 3 ? 'selected' : '' }}>Conference</option>
        </select>

           <select name="type_id" id="type_filter_forms" class="filter-input" required>
            <option value="">Select Type</option>
        </select>
        </div>

        <div class="mt-6 flex justify-end">
            <button class="btn-base btn-primary px-8">
                🚀 Upload Form
            </button>
        </div>
    </form>
</div>

<!-- FORMS LIST -->
<div class="bg-gray-50 p-6 rounded-xl">

    <h3 class="section-title mb-6">
        📂 Existing Forms
    </h3>

    @php
        $grouped = $forms->groupBy(function ($item) {
            return ($item->category->name ?? 'Uncategorized') . ' — ' . ($item->type->name ?? 'General');
        });
    @endphp

    @forelse($grouped as $groupLabel => $items)

        <div class="mb-10">

            <div class="flex items-center gap-3 mb-4">
                <span class="group-line"></span>
                <div class="group-title">{{ $groupLabel }}</div>
                <span class="group-line"></span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                @foreach($items as $form)

                    <div class="card p-5">

                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h5 class="font-bold text-gray-800">
                                    {{ $form->name }}
                                </h5>
                                <span class="text-xs text-gray-500">
                                    {{ $form->label ?? 'No Label' }}
                                </span>
                            </div>

                            <span class="text-xs text-gray-400">
                                {{ $form->updated_at->format('d/m/y') }}
                            </span>
                        </div>

                        <!-- ACTIONS -->
                        <div class="flex gap-2 mt-4">

                            <a href="{{ asset('storage/' . $form->file_path) }}"
                               target="_blank"
                               class="btn-base btn-view w-full">
                                View
                            </a>

                            <a href="{{ route('form.download', $form->id) }}"
                               class="btn-base btn-primary w-full">
                                Download
                            </a>

                        </div>

                        <!-- REPLACE -->
                        <form method="POST"
                              action="{{ route('admin.forms.update', $form->id) }}"
                              enctype="multipart/form-data"
                              class="mt-4 flex gap-2">

                            @csrf

                            <input type="file" name="file" class="form-input-custom text-sm">

                            <button class="btn-base btn-warning whitespace-nowrap">
                                🔄 Replace
                            </button>

                        </form>

                        <!-- DELETE (FIXED) -->
                        <form method="POST"
                              action="{{ route('admin.forms.destroy', $form->id) }}"
                              onsubmit="return confirm('Are you sure you want to delete this form?');"
                              class="mt-2">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn-base btn-danger text-xs px-3 py-2 ">
                                🗑 Delete Form
                            </button>

                        </form>

                    </div>

                @endforeach

            </div>

        </div>

    @empty
        <p class="text-center text-gray-400 py-10">No forms uploaded yet.</p>
    @endforelse

</div>
</div>
<!-- =========================
     DOCUMENT REQUIREMENTS
========================= -->
<div id="docs-section" class="hidden">
<div class="card p-6 mt-10">

    <h3 class="text-lg font-bold mb-5 text-gray-700">
        📋 Manage Document Requirements
    </h3>

    <!-- ADD NEW DOCUMENT -->
    <form method="POST" action="{{ route('admin.documents.store') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <input type="text" name="label"
                   placeholder="Document Label (e.g. Justification Letter)"
                   class="form-input-custom" required>

            <input type="text" name="input_name"
                   placeholder="Input Name (e.g. justification)"
                   class="form-input-custom" required>

            <select name="section" class="form-input-custom" required>
                <option value="">Section</option>
                <option value="main">Main Form</option>
                <option value="evidence">Evidence</option>
                <option value="supporting">Supporting</option>
            </select>

            <select name="is_required" class="form-input-custom">
                <option value="1">Required</option>
                <option value="0">Optional</option>
            </select>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
           <select name="category_id" id="cat_filter_docs" class="filter-input" required>
            <option value="">Select Category</option>
            <option value="1" {{ request('category_id') == 1 ? 'selected' : '' }}>Publication</option>
            <option value="2" {{ request('category_id') == 2 ? 'selected' : '' }}>Grant</option>
            <option value="3" {{ request('category_id') == 3 ? 'selected' : '' }}>Conference</option>
        </select>

           <select name="type_id" id="type_filter_docs" class="filter-input" required>
            <option value="">Select Type</option>
        </select>

        </div>

        <div class="mt-6 text-right">
            <button class="btn-base btn-primary px-6">
                + Add Document
            </button>
        </div>
    </form>
</div>
<div class="bg-gray-50 p-6 rounded-xl mt-6">

    <h3 class="section-title mb-6">
        📂 Existing Document Requirements
    </h3>

    @php
        $groupedDocs = $documents->groupBy(function ($item) {
            return ($item->category->name ?? 'No Category') . ' — ' . ($item->type->name ?? 'No Type');
        });
    @endphp

    @forelse($groupedDocs as $group => $items)

        <div class="mb-8">

            <div class="flex items-center gap-3 mb-4">
                <span class="group-line"></span>
                <div class="group-title">{{ $group }}</div>
                <span class="group-line"></span>
            </div>

            <table class="w-full text-sm bg-white rounded-lg overflow-hidden">
                <thead>
                    <tr style="background:#edf2f7;">
                        <th class="p-3 text-left">Label</th>
                        <th class="p-3 text-left">Input Name</th>
                        <th class="p-3 text-left">Section</th>
                        <th class="p-3 text-left">Required</th>
                        <th class="p-3 text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($items as $doc)
                    <tr class="border-t">
                        <td class="p-3">{{ $doc->label }}</td>
                        <td class="p-3 text-gray-500">{{ $doc->input_name }}</td>
                        <td class="p-3 capitalize">{{ $doc->section }}</td>
                        <td class="p-3">
                            @if($doc->is_required)
                                <span class="text-green-600 font-semibold">Yes</span>
                            @else
                                <span class="text-gray-400">No</span>
                            @endif
                        </td>

                        <td class="p-3 text-center flex gap-2 justify-center">

                            <!-- TOGGLE REQUIRED -->
                            <form method="POST" action="{{ route('admin.documents.toggle', $doc->id) }}">
                                @csrf
                                <button class="btn-base btn-warning text-xs">
                                     Toggle
                                </button>
                            </form>

                            <!-- DELETE -->
                            <form method="POST"
                                  action="{{ route('admin.documents.destroy', $doc->id) }}"
                                  onsubmit="return confirm('Delete this document?');">
                                @csrf
                                @method('DELETE')

                                <button class="btn-base btn-danger text-xs">
                                    🗑
                                </button>
                            </form>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    @empty
        <p class="text-gray-400 text-center">No document requirements yet.</p>
    @endforelse

</div>
</div>

</div>
<script>
const adminTypesMap = {
    "1": [
        {id: 1, name: 'Funding'},
        {id: 2, name: 'Reward'}
    ],
    "2": [
        {id: 3, name: 'General'},
        {id: 4, name: 'Purchase'},
        {id: 5, name: 'GRA/RA'},
        {id: 8, name: 'Virement'}
    ],
    "3": [
        {id: 6, name: 'Local'},
        {id: 7, name: 'Overseas'}
    ]
};

/* ================= FORMS FILTER ================= */
function initFormsFilter() {
    const cat = document.getElementById('cat_filter_forms');
    const type = document.getElementById('type_filter_forms');

    if (!cat || !type) return;

    const selectedType = "{{ request('type_id') }}";

    function update() {
        type.innerHTML = '<option value="">Select Type</option>';

        if (adminTypesMap[cat.value]) {
            adminTypesMap[cat.value].forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.name;

                if (String(item.id) === String(selectedType)) {
                    opt.selected = true;
                }

                type.appendChild(opt);
            });
        }
    }

    cat.addEventListener('change', update);
    update();
}

/* ================= DOCS FILTER ================= */
function initDocsFilter() {
    const cat = document.getElementById('cat_filter_docs');
    const type = document.getElementById('type_filter_docs');

    if (!cat || !type) return;

    const selectedType = "{{ request('type_id') }}";

    function update() {
        type.innerHTML = '<option value="">Select Type</option>';

        if (adminTypesMap[cat.value]) {
            adminTypesMap[cat.value].forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.name;

                if (String(item.id) === String(selectedType)) {
                    opt.selected = true;
                }

                type.appendChild(opt);
            });
        }
    }

    cat.addEventListener('change', update);
    update();
}

document.addEventListener('DOMContentLoaded', function () {
    initFormsFilter();
    initDocsFilter();
});

function showTab(tab) {
    const forms = document.getElementById('forms-section');
    const docs = document.getElementById('docs-section');

    const tabForms = document.getElementById('tab-forms');
    const tabDocs = document.getElementById('tab-docs');

    if (tab === 'forms') {
        forms.classList.remove('hidden');
        docs.classList.add('hidden');

        tabForms.classList.add('border-blue-600', 'text-blue-600');
        tabForms.classList.remove('text-gray-500');

        tabDocs.classList.remove('border-blue-600', 'text-blue-600');
        tabDocs.classList.add('text-gray-500');
    }

    if (tab === 'docs') {
        docs.classList.remove('hidden');
        forms.classList.add('hidden');

        tabDocs.classList.add('border-blue-600', 'text-blue-600');
        tabDocs.classList.remove('text-gray-500');

        tabForms.classList.remove('border-blue-600', 'text-blue-600');
        tabForms.classList.add('text-gray-500');
    }
}
</script>
</x-app-layout>
