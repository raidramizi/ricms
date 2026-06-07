<x-app-layout>
<x-slot name="title">
    Recommended Applications
</x-slot>
<style>
body { background:#f3f4f6; }

.filter-bar{
    display:flex;
    gap:12px;
    align-items:flex-end;
    flex-wrap:wrap;
    background:#fff;
    padding:12px;
    border-radius:10px;
}

.filter-bar > div{
    min-width:180px;
}

.filter-input{
    width:100%;
    padding:6px 8px;
    border:1px solid #e5e7eb;
    border-radius:6px;
    font-size:12px;
    outline:none;
}

.filter-input:focus{
    border-color:#2563eb;
}

table th { color:white !important; }
table td { color:#111827 !important; }

.btn{
    padding:6px 12px;
    border:none;
    border-radius:6px;
    font-size:12px;
    cursor:pointer;
    color:white;
    text-decoration:none;
}

.btn-view{ background:#2563eb; }
.btn-download{ background:#0f766e; }

.section-title{
    margin-top:18px;
    font-weight:bold;
    font-size:13px;
    text-transform:uppercase;
    color:#475569;
}

.file-box{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:10px;
    border:1px solid #e5e7eb;
    border-radius:8px;
    margin-top:8px;
    background:#fff;
}

.file-info small{ color:gray; font-size:12px; }
</style>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Recommended Applications
    </h2>
</x-slot>

<div style="padding:24px;">

    <div style="display:flex; justify-content:space-between; margin-bottom:20px;">
        <form action="{{ url()->current() }}" method="GET" class="filter-bar" style="width:100%;">

    {{-- Search --}}
    <div style="flex:1;">
        <label style="font-size:12px; font-weight:bold;">Search Name</label>
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               class="filter-input"
               placeholder="Enter staff name...">
    </div>

    {{-- Category --}}
    <div style="flex:1;">
        <label style="font-size:12px; font-weight:bold;">Category</label>
        <select name="category_id" id="cat_filter" class="filter-input">
            <option value="">All Categories</option>
            <option value="1" {{ request('category_id') == 1 ? 'selected' : '' }}>Publication</option>
            <option value="2" {{ request('category_id') == 2 ? 'selected' : '' }}>Grant</option>
            <option value="3" {{ request('category_id') == 3 ? 'selected' : '' }}>Conference</option>
        </select>
    </div>

    {{-- Type --}}
    <div style="flex:1;">
        <label style="font-size:12px; font-weight:bold;">Type</label>
        <select name="type_id" id="type_filter" class="filter-input">
            <option value="">All Types</option>
        </select>
    </div>

    {{-- Buttons --}}
    <div style="display:flex; gap:8px; align-items:flex-end;">
        <button type="submit" class="btn" style="background:#4413c0;">
            Apply
        </button>

        <a href="{{ url()->current() }}" class="btn"
           style="background:#edf2f7; color:#4a5568;">
            Reset
        </a>
    </div>

</form>

        <div style="background:#2563eb; color:white; padding:8px 14px; border-radius:8px;">
            Total: {{ $submissions->total() }}
        </div>
    </div>

    <div style="background:white; border-radius:12px; overflow:hidden;">

        <table style="width:100%; border-collapse:collapse;">
            <thead style="background:#111827;">
                <tr>
                    <th style="padding:14px;">No</th>
                    <th style="padding:14px;">Staff</th>
                    <th style="padding:14px;">Type</th>
                    <th style="padding:14px;">Category</th>
                    <th style="padding:14px;">Head Comment</th>
                    <th style="padding:14px;">Status</th>
                    <th style="padding:14px;">Done</th>
                    <th style="padding:14px; text-align:center;">Action</th>
                </tr>
            </thead>

            <tbody>
            @foreach($submissions as $index => $s)
                <tr style="border-bottom:1px solid #e5e7eb;">

                    <td style="padding:14px; font-weight:600;">
                     {{ $submissions->firstItem() + $index }}
                    </td>

                    <td style="padding:14px; font-weight:600;">
                        {{ $s->user_name }}
                    </td>

                    <td style="padding:14px;">
                        {{ $s->type->name ?? '-' }}
                    </td>

                    <td style="padding:14px;">
                        {{ $s->category->name ?? '-' }}
                    </td>

                    <td style="padding:14px;">
                        <div style="background:#e0f2fe; padding:6px 10px; border-radius:6px; font-size:12px;">
                            {{ Str::limit($s->head_internal_comment ?? 'No comment', 40) }}
                        </div>
                    </td>

                    {{-- STATUS --}}
                  <td style="padding:14px;">
    @if($s->completion && $s->completion->status == 'approved')
        <span style="background:#0ac24b; color:#166534; padding:5px 10px; border-radius:20px; font-size:12px;">
            Approved
        </span>

    @else
        <span style="background:#2cec45; color:#000000; padding:5px 10px; border-radius:20px; font-size:12px;">
            Recommended
        </span>
    @endif
</td>

                    {{-- DONE --}}
                    <td style="padding:14px;">
                        @if($s->completion && $s->completion->status == 'approved')
                            <span style="background:#dcfce7; color:#166534; padding:5px 10px; border-radius:10px; font-size:12px;">
                                Done <br>
                                {{ \Carbon\Carbon::parse($s->completion->done_at)->format('d M Y, h:i A') }}
                            </span>
                        @else
                            <span style="color:#9ca3af;">Pending</span>
                        @endif
                    </td>

                    <td style="padding:14px; text-align:center;">
                        <button onclick="openModal({{ $s->id }})"
                                class="btn btn-view">
                            View
                        </button>

                        {{-- DONE BUTTON (ONLY AFTER UPLOAD) --}}
                        @if(!$s->completion && optional($s->approval)->proof_file)
                        <form method="POST" action="{{ route('admin.submission.markDone', $s->id) }}" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button class="btn" style="background:#16a34a;">Done</button>
                        </form>
                        @endif
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

    <div style="margin-top:16px;">
        {{ $submissions->links() }}
    </div>

</div>

{{-- MODALS --}}
@foreach($submissions as $s)

<div id="modal-{{ $s->id }}"
     style="display:none; position:fixed; inset:0; z-index:9999;">

    {{-- BACKDROP --}}
    <div onclick="closeModal({{ $s->id }})"
         style="position:absolute; inset:0; background:rgba(0,0,0,0.6);">
    </div>

    {{-- MODAL BOX --}}
    <div style="position:relative; max-width:900px; margin:60px auto; background:white; border-radius:12px; overflow:hidden;">

        {{-- HEADER --}}
        <div style="background:#111827; color:white; padding:16px; display:flex; justify-content:space-between; align-items:center;">
            <h2>Submission Details</h2>
            <button onclick="closeModal({{ $s->id }})" style="color:white; font-size:18px;">
                ✕
            </button>
        </div>

        {{-- CONTENT --}}
        <div style="padding:20px; max-height:80vh; overflow-y:auto;">

            {{-- ================= BASIC INFO ================= --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div><b>Staff:</b><br>{{ $s->user_name }}</div>
                <div><b>Type:</b><br>{{ $s->type->name ?? '-' }}</div>
                <div><b>Category:</b><br>{{ $s->category->name ?? '-' }}</div>
                <div><b>Reviewed:</b><br>{{ $s->head_reviewed_at ?? '-' }}</div>
            </div>

            {{-- ================= MAIN FORM ================= --}}
            <div class="section-title">Main Form</div>

            @if($s->form_file)
                <div class="file-box">
                    <div class="file-info">
                        <b>{{ $s->form_file_name }}</b>
                    </div>

                    <div>
                        <a href="{{ asset('storage/'.$s->form_file) }}"
                           target="_blank"
                           class="btn btn-view">
                            VIEW
                        </a>

                        <a href="{{ route('secure.download', [
                            'path' => $s->form_file,
                            'name' => $s->form_file_name
                        ]) }}" class="btn btn-download">
                            DOWNLOAD
                        </a>
                    </div>
                </div>
            @endif

            {{-- ================= EVIDENCE FILES ================= --}}
            <div class="section-title">Evidence Files</div>

            @php
                $evidence = is_string($s->evidence_files)
                    ? json_decode($s->evidence_files, true)
                    : $s->evidence_files;
            @endphp

            @forelse($evidence ?? [] as $key => $file)
                <div class="file-box">

                    <div class="file-info">

                        <b>{{ $file['original_name'] }}</b>
                    </div>

                    <div>
                        <a href="{{ asset('storage/'.$file['path']) }}"
                           target="_blank"
                           class="btn btn-view">
                            VIEW
                        </a>

                        <a href="{{ route('secure.download', [
                            'path' => $file['path'],
                            'name' => $file['original_name']
                        ]) }}" class="btn btn-download">
                            DOWNLOAD
                        </a>
                    </div>

                </div>
            @empty
                <p>No evidence files</p>
            @endforelse

            {{-- ================= OPTIONAL FILES ================= --}}
            <div class="section-title">Supporting Documents</div>

            @php
                $optional = is_string($s->evidence_optional)
                    ? json_decode($s->evidence_optional, true)
                    : $s->evidence_optional;
            @endphp

            @forelse($optional ?? [] as $file)
                <div class="file-box">

                    <div class="file-info">

                        <b>{{ $file['original_name'] }}</b>
                    </div>

                    <div>
                        <a href="{{ asset('storage/'.$file['path']) }}"
                           target="_blank"
                           class="btn btn-view">
                            VIEW
                        </a>

                        <a href="{{ route('secure.download', [
                            'path' => $file['path'],
                            'name' => $file['original_name']
                        ]) }}" class="btn btn-download">
                            DOWNLOAD
                        </a>
                    </div>

                </div>
            @empty
                <p>No optional files</p>
            @endforelse

            {{-- ================= COMMENT ================= --}}
            <div style="margin-top:20px; background:#fef3c7; padding:12px; border-radius:6px;">
                <b>Head Comment</b>
                <p>{{ $s->head_internal_comment ?? 'No comment' }}</p>
            </div>

            {{-- PROOF SECTION 🔥 --}}
            <div class="section-title">Approval Document</div>

            @if($s->approval && $s->approval->proof_file)

                <div class="file-box">
                    <div class="file-info">
                        <b>{{ $s->approval->proof_name }}</b>
                    </div>

                    <div>
                        <a href="{{ asset('storage/'.$s->approval->proof_file) }}" target="_blank" class="btn btn-view">VIEW</a>
                    </div>
                </div>

            @else

                <form method="POST"
                      action="{{ route('admin.uploadProof', $s->id) }}"
                      enctype="multipart/form-data">

                    @csrf

                    <input type="file" name="proof_file" required>

                    <button type="submit"
                            class="btn"
                            style="background:#2563eb; margin-top:10px;">
                        Upload Document
                    </button>

                </form>

            @endif


        </div>

    </div>
</div>

@endforeach

<script>
function openModal(id){
    document.getElementById('modal-'+id).style.display='block';
    document.body.style.overflow='hidden';
}

function closeModal(id){
    document.getElementById('modal-'+id).style.display='none';
    document.body.style.overflow='auto';
}

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

function initTypeFilter() {
    const cat = document.getElementById('cat_filter');
    const type = document.getElementById('type_filter');

    const selectedType = "{{ request('type_id') }}";

    function updateTypes() {
        const selectedCat = cat.value;

        type.innerHTML = '<option value="">All Types</option>';

        if (adminTypesMap[selectedCat]) {
            adminTypesMap[selectedCat].forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.name;

                // keep selected after reload
                if (String(item.id) === String(selectedType)) {
                    opt.selected = true;
                }

                type.appendChild(opt);
            });
        }
    }

    cat.addEventListener('change', function () {
        updateTypes();
    });

    updateTypes(); // initial load
}

document.addEventListener('DOMContentLoaded', initTypeFilter);

</script>

</x-app-layout>
