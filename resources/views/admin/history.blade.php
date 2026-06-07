<x-app-layout>

{{-- ===================== STYLES ===================== --}}
<style>
body {
    background: #f3f4f6;
}

/* CARD */
.dashboard-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    border: 1px solid #e5e7eb;
    overflow: hidden;
}

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
    border-color:#4a782d;
}

/* BUTTONS */
.btn {
    padding: 7px 12px;
    font-size: 12px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}

.btn-apply {
    background: #4413c0;
    color: white;
}

.btn-reset {
    background: #e5e7eb;
    color: #0b1e3e;
}

.btn-view {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}

.btn-download {
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
}

/* TABLE */
.smart-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.smart-table thead {
    background: linear-gradient(to right, #111827, #1f2937);
    color: #fff;
}

.smart-table th {
    padding: 14px;
    text-align: center;
}

.smart-table td {
    padding: 12px;
    border-bottom: 1px solid #f1f5f9;
    text-align: center;
    vertical-align: middle;
}

.smart-table tbody tr:hover {
    background: #f9fafb;
}

/* BADGE */
.badge {
    padding: 5px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

/* TOP BAR */
.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}
</style>
<x-slot name="title">
    History
</x-slot>

{{-- ===================== HEADER ===================== --}}
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Approval History
    </h2>
</x-slot>

<div style="padding:24px;">

   {{-- ===================== TOP FILTER ===================== --}}
{{-- ===================== TOP FILTER (FIXED ALIGNMENT) ===================== --}}
<div style="display:flex; gap:12px; margin-bottom:20px; align-items:stretch;">

    {{-- FILTER FORM --}}
    <form action="{{ url()->current() }}"
          method="GET"
          class="filter-bar"
          style="flex:1; display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap;">

        {{-- SEARCH --}}
        <div style="flex:1; min-width:180px;">
            <label style="font-size:12px; font-weight:bold;">Search Name</label>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   class="filter-input"
                   placeholder="Enter staff name...">
        </div>

        {{-- CATEGORY --}}
        <div style="flex:1; min-width:180px;">
            <label style="font-size:12px; font-weight:bold;">Category</label>
            <select name="category_id" id="cat_filter" class="filter-input">
                <option value="">All Categories</option>
                <option value="1" {{ request('category_id') == 1 ? 'selected' : '' }}>Publication</option>
                <option value="2" {{ request('category_id') == 2 ? 'selected' : '' }}>Grant</option>
                <option value="3" {{ request('category_id') == 3 ? 'selected' : '' }}>Conference</option>
            </select>
        </div>

        {{-- TYPE --}}
        <div style="flex:1; min-width:180px;">
            <label style="font-size:12px; font-weight:bold;">Type</label>
            <select name="type_id" id="type_filter" class="filter-input">
                <option value="">All Types</option>
            </select>
        </div>

        {{-- BUTTONS --}}
        <div style="display:flex; gap:8px; align-items:flex-end; min-width:180px;">

            <button type="submit" class="btn btn-apply">
                Apply
            </button>

            <a href="{{ url()->current() }}" class="btn btn-reset">
                Reset
            </a>

        </div>

    </form>

    {{-- TOTAL BOX --}}
    <div style="display:flex;flex-direction:column;align-items:center; justify-content:center; min-width:140px; padding:10px 14px;
    background:#2563eb; color:white; border-radius:10px; font-weight:600;font-size:13px; align-self:stretch; text-align:center; line-height:1.4;">

    <div style="margin-top:4px;">
        Total Approved History :
    </div>

    <div style="font-size:16px; font-weight:700;">
        {{ $submissions->total() }}
    </div>

</div>

</div>
{{-- ===================== TABLE ===================== --}}
<div class="dashboard-card">

<table class="smart-table">

<thead>
<tr>
    <th>No</th>
    <th>Staff</th>
    <th>Type</th>
    <th>Category</th>
    <th>Status</th>
    <th>Done At</th>
    <th>Attachment</th>
</tr>
</thead>

<tbody>
@forelse($submissions as $index => $s)
<tr>
    <td>{{ $submissions->firstItem() + $index }}</td>
    <td>{{ $s->user->name ?? '-' }}</td>
    <td>{{ $s->type->name ?? '-' }}</td>
    <td>{{ $s->category->name ?? '-' }}</td>

    <td><span class="badge">Approved</span></td>

    <td>{{ $s->completion->done_at ?? '-' }}</td>

    <td>
        @if($s->approval && $s->approval->proof_file)
            <div style="display:flex;justify-content:center;gap:6px;">
                <a href="{{ asset('storage/'.$s->approval->proof_file) }}"
                   target="_blank"
                   class="btn btn-view">View</a>

                <a href="{{ route('secure.download', [
                    'path' => $s->approval->proof_file,
                    'name' => $s->approval->proof_name
                ]) }}"
                   class="btn btn-download">Download</a>
            </div>
        @else
            <span style="color:#9ca3af;">No file</span>
        @endif
    </td>
</tr>
@empty
<tr>
    <td colspan="7" style="padding:20px;color:#9ca3af;">
        No data found
    </td>
</tr>
@endforelse
</tbody>

</table>

</div>

{{-- ===================== PAGINATION ===================== --}}
<div style="margin-top:16px;">
    {{ $submissions->appends(request()->query())->links() }}
</div>

</div>

{{-- ===================== SCRIPT ===================== --}}
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

function initFilters() {
    const cat = document.getElementById('cat_filter');
    const type = document.getElementById('type_filter');

    const selectedType = "{{ request('type_id') }}";

    function loadTypes() {
        const selectedCat = cat.value;

        type.innerHTML = '<option value="">All Types</option>';

        if (adminTypesMap[selectedCat]) {
            adminTypesMap[selectedCat].forEach(item => {
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

    cat.addEventListener('change', loadTypes);

    loadTypes();
}

document.addEventListener('DOMContentLoaded', initFilters);
</script>

</x-app-layout>
