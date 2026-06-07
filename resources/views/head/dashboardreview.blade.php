<x-app-layout>

<style>
/* ================= FILTER ================= */
.filter-bar {
    background: #fff;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: flex-end;
    border: 1px solid #eee;
}

.filter-input {
    border: 1px solid #ddd;
    padding: 8px;
    border-radius: 5px;
    font-size: 14px;
    min-width: 180px;
}

/* ================= TABLE ================= */
.table-container {
    width: 100%;
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    overflow-x: auto;
    border: 1px solid #eee;
}

.custom-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1000px;
}

.custom-table th {
    background: #f8fafc;
    padding: 14px;
    text-align: left;
    border-bottom: 2px solid #eee;
}

.custom-table td {
    padding: 14px;
    border-bottom: 1px solid #f1f1f1;
}

.custom-table tr:hover {
    background: #fafafa;
}

/* ================= BUTTONS ================= */
.btn {
    padding: 7px 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    color: white;
    font-size: 12px;
    text-decoration: none;
}

.btn-view { background: #3490dc; }
.btn-download { background: #0f766e; }
.btn-edit { background: #f59e0b; }
.btn-save { background: #16a34a; }
.btn-approve { background: #16a34a; }
.btn-reject { background: #dc2626; }

/* ================= BADGE ================= */
.badge {
    padding: 5px 10px;
    border-radius: 5px;
    color: white;
    font-size: 11px;
}

.badge-purple { background: #6c5ce7; }
.badge-green { background: #16a34a; }
.badge-red { background: #dc2626; }
.badge-yellow { background: #f59e0b; }

/* ===== MODAL ===== */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            padding: 20px;
        }

        .modal-content {
            background: white;
            width: 900px;
            max-width: 100%;
            margin: auto;
            border-radius: 14px;
            padding: 25px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }

        .file-box {
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            background: #fff;
        }

        .file-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .file-title {
            font-weight: bold;
            color: #1e293b;
        }

        .review-box {
            margin-top: 25px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 20px;
            border-radius: 10px;
        }

        textarea {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 10px;
            min-height: 100px;
        }

        .upload-input {
            margin-top: 12px;
            width: 100%;
            border: 1px dashed #cbd5e1;
            padding: 12px;
            border-radius: 8px;
            background: #f8fafc;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 12px;
            margin-top: 20px;
            text-transform: uppercase;
            color: #475569;
        }

        .verify-check {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

/* ================= CHART PANEL (SMALL FIX) ================= */
#chartPanel {
    display: none;
    margin-top: 20px;
    background: #fff;
    padding: 15px;
    border-radius: 12px;
    border: 1px solid #eee;
}

.chart-wrap {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    align-items: flex-start;
}

.chart-box {
    flex: 1;
    min-width: 280px;
    max-width: 500px;
    height: 220px;
}

/* IMPORTANT: force small charts */
canvas {
    width: 100% !important;
    height: 220px !important;
}
.status-time { font-size: 10px; color: #718096; margin-top: 5px; font-weight: 500; display: block; }
</style>
<x-slot name="title">
    Head Dashboard
</x-slot>
<x-slot name="header">
    <h2 class="text-xl font-semibold text-gray-800">
        Head Dashboard
    </h2>
</x-slot>

<div class="p-6">

{{-- ================= CHART TOGGLE ================= --}}
<button onclick="toggleChartPanel()" class="btn" style="background:#4f46e5;">
    📊 Head Analytics
</button>

<div id="chartPanel">
    <div class="chart-wrap">
        <div class="chart-box">
            <canvas id="statusChart"></canvas>
        </div>

        <div class="chart-box">
            <canvas id="trendChart"></canvas>
        </div>
    </div>
</div>

{{-- ================= KPI ================= --}}
<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:20px; margin:25px 0;">

    <div style="background:#4f46e5;color:#fff;padding:20px;border-radius:12px;">
        Pending<br><b style="font-size:30px;">{{ $pendingHead }}</b>
    </div>

    <div style="background:#16a34a;color:#fff;padding:20px;border-radius:12px;">
        Recommended<br><b style="font-size:30px;">{{ $recommended }}</b>
    </div>

    <div style="background:#dc2626;color:#fff;padding:20px;border-radius:12px;">
        Rejected<br><b style="font-size:30px;">{{ $rejected }}</b>
    </div>

    <div style="background:#111827;color:#fff;padding:20px;border-radius:12px;">
        Total<br><b style="font-size:30px;">{{ $total }}</b>
    </div>

</div>

{{-- ================= FILTER ================= --}}
<form action="{{ url()->current() }}" method="GET" class="filter-bar">
        <div style="display:flex; flex-direction:column; gap:5px; flex: 1;">
            <label style="font-size:12px; font-weight:bold;">Search Name</label>
            <input type="text" name="search" value="{{ request('search') }}" class="filter-input" placeholder="Enter staff name...">
        </div>

        <div style="display:flex; flex-direction:column; gap:7px;">
            <label style="font-size:12px; font-weight:bold;">Category</label>
            <select name="category_id" id="cat_filter" class="filter-input">
                <option value="">All Categories</option>
                <option value="1" {{ request('category_id') == 1 ? 'selected' : '' }}>Publication</option>
                <option value="2" {{ request('category_id') == 2 ? 'selected' : '' }}>Grant</option>
                <option value="3" {{ request('category_id') == 3 ? 'selected' : '' }}>Conference</option>
            </select>
        </div>

        <div style="display:flex; flex-direction:column; gap:7px;">
            <label style="font-size:12px; font-weight:bold;">Type</label>
            {{-- This dropdown is controlled via JavaScript below --}}
            <select name="type_id" id="type_filter" class="filter-input">
                <option value="">Select Type</option>
            </select>
        </div>
        <div style="display:flex; flex-direction:column; gap:7px;">
    <label style="font-size:12px; font-weight:bold;">Status</label>
    <select name="status" class="filter-input">
        <option value="">All Status</option>
        <option value="sent_to_head" {{ request('status') == 'sent_to_head' ? 'selected' : '' }}>
            Pending
        </option>
        <option value="approved_head" {{ request('status') == 'approved_head' ? 'selected' : '' }}>
            Recommended
        </option>
        <option value="rejected_head" {{ request('status') == 'rejected_head' ? 'selected' : '' }}>
            Rejected
        </option>
    </select>
</div>

        <button type="submit" class="btn" style="background:#4a5568;">Apply</button>
        <a href="{{ url()->current() }}" class="btn" style="background:#edf2f7; color:#4a5568;">Reset</a>
    </form>

{{-- ================= TABLE ================= --}}
<div class="table-container">

<table class="custom-table">
<thead>
<tr>
    <th>No</th>
    <th>Staff</th>
    <th>Category</th>
    <th>Type</th>
    <th>Status</th>
    <th>Documents</th>
    <th>Action</th>
</tr>
</thead>

<tbody>
@forelse($submissions as $i => $s)
<tr>
    <td>{{ $submissions->firstItem() + $i }}</td>
    <td>{{ $s->user->name ?? '-' }}
         <div style="font-size:11px;color:gray;">Sent by admin: {{ $s->sent_to_head_at?->format('d M Y') }}</div>
    </td>
    <td>{{ $s->category->name ?? '-' }}</td>
    <td>{{ $s->type->name ?? '-' }}</td>

  <td>
                                @if($s->status == 'sent_to_head')
                                    <span class="badge badge-purple">Pending Head Review</span>
                                @elseif($s->status == 'approved_head')
                                    <span class="badge badge-green">Recommended</span>
                                @elseif($s->status == 'rejected_head')
                                    <span class="badge badge-red">Rejected</span>
                                @else
                                    <span class="badge badge-yellow">Pending</span>
                                @endif
                                @if($s->updated_at && $s->status)
                                   <div class="status-time">
                                    Updated: {{ $s->updated_at->format('d M, h:i A') }}
                                   </div>
                                  @endif
                            </td>

    <td>
        <button class="btn btn-view" onclick="openModal({{ $s->id }})">Open</button>
    </td>

    <td>
        {{ $s->status=='sent_to_head'?'Waiting':'Done' }}
    </td>
</tr>
@empty
<tr><td colspan="7">No data</td></tr>
@endforelse
</tbody>
</table>

</div>


</div>
<div>
<button onclick="downloadReport()" class="btn btn-view" style="background:#2d3748;">
    Download Report (PDF)
</button>
</div>
</br>

{{-- ================= MODALS ================= --}}
@foreach($submissions as $s)
@php
    $isEditable = $s->status === 'sent_to_head';

    $evidence = is_string($s->evidence_files)
        ? json_decode($s->evidence_files, true)
        : $s->evidence_files;

    $optional = is_string($s->evidence_optional)
        ? json_decode($s->evidence_optional, true)
        : $s->evidence_optional;
@endphp

<div id="modal-{{ $s->id }}" class="modal">
    <div class="modal-content">

        <span onclick="closeModal({{ $s->id }})"
              style="position:absolute; top:15px; right:20px; cursor:pointer; font-size:28px;">
            &times;
        </span>

        <h2 style="font-size:22px; font-weight:bold; margin-bottom:20px;">
            Head Review Panel
        </h2>

        @unless($isEditable)
            <div style="background:#eef2ff; padding:10px; border-radius:8px; margin-bottom:15px;">
                <b>READ ONLY MODE:</b> Decision already finalized
            </div>
        @endunless

        {{-- ================= MAIN FORM ================= --}}
        <div class="section-title">Main Form</div>

        @if($s->form_file)
            <div class="file-box">
                <div class="file-header">
                    <div class="file-title">{{ $s->form_file_name }}</div>

                    <div style="display:flex; justify-content:flex-end; width:100%; gap:8px; flex-wrap:wrap;">

                        <a href="{{ asset('storage/'.$s->form_file) }}"
                           target="_blank"
                           class="btn btn-view">
                            VIEW
                        </a>

                        <a href="{{ route('secure.download', ['path' => $s->form_file, 'name' => $s->form_file_name]) }}"
                           class="btn btn-download">
                            DOWNLOAD
                        </a>

                        @if($isEditable)
                            <button type="button"
                                    class="btn btn-edit"
                                    onclick="toggleEdit('main-{{ $s->id }}')">
                                EDIT
                            </button>
                        @endif

                    </div>
                </div>

                {{-- Upload Form --}}
                @if($isEditable)
                    <div id="main-{{ $s->id }}" style="display:none; margin-top:15px;">
                        <form method="POST"
                              action="{{ route('head.submissions.updateDocuments', $s->id) }}"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="replace_main_form" required class="upload-input">
                            <button type="submit" class="btn btn-save" style="margin-top:10px;">
                                Save
                            </button>
                        </form>
                    </div>
                @endif

                {{-- Checkbox --}}
                <div style="display:flex; align-items:center; gap:8px; margin-top:12px;">
                    <input type="checkbox"
                           class="verify-check verify-group-{{ $s->id }}"
                           {{ $isEditable ? '' : 'disabled checked' }}>
                    <span style="font-size:12px; font-weight:600;">Checked</span>
                </div>

            </div>
        @endif

        {{-- ================= EVIDENCE ================= --}}
        <div class="section-title">Evidence Documents</div>

        @forelse($evidence ?? [] as $key => $file)
            <div class="file-box">
                <div class="file-header">
                    <div>

                        <div><b>
                            {{ $file['original_name'] }}</b>
                        </div>
                    </div>

                    <div style="display:flex; justify-content:flex-end; width:100%; gap:8px; flex-wrap:wrap;">

                        <a href="{{ asset('storage/'.$file['path']) }}"
                           target="_blank"
                           class="btn btn-view">
                            VIEW
                        </a>

                        <a href="{{ route('secure.download', ['path' => $file['path'], 'name' => $file['original_name']]) }}"
                           class="btn btn-download">
                            DOWNLOAD
                        </a>

                        @if($isEditable)
                            <button type="button"
                                    class="btn btn-edit"
                                    onclick="toggleEdit('evidence-{{ $s->id }}-{{ $key }}')">
                                EDIT
                            </button>
                        @endif

                    </div>
                </div>

                {{-- Upload Evidence --}}
                @if($isEditable)
                    <div id="evidence-{{ $s->id }}-{{ $key }}" style="display:none; margin-top:15px;">
                        <form method="POST"
                              action="{{ route('head.submissions.updateDocuments', $s->id) }}"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="replace_evidence[{{ $key }}]" required class="upload-input">
                            <button type="submit" class="btn btn-save" style="margin-top:10px;">
                                Save
                            </button>
                        </form>
                    </div>
                @endif

                {{-- Checkbox --}}
                <div style="display:flex; align-items:center; gap:8px; margin-top:12px;">
                    <input type="checkbox"
                           class="verify-check verify-group-{{ $s->id }}"
                           {{ $isEditable ? '' : 'disabled checked' }}>
                    <span style="font-size:12px; font-weight:600;">Checked</span>
                </div>

            </div>
        @empty
            <p style="font-size:12px; color:gray;">No evidence uploaded.</p>
        @endforelse
         {{-- ================= support ================= --}}
        <div class="section-title">Supporting Documents</div>

        @forelse($optional ?? [] as $key => $file)
            <div class="file-box">
                <div class="file-header">
                    <div>

                        <div ><b>
                            {{ $file['original_name'] }}</b>
                        </div>
                    </div>

                    <div style="display:flex; justify-content:flex-end; width:100%; gap:8px; flex-wrap:wrap;">

                        <a href="{{ asset('storage/'.$file['path']) }}"
                           target="_blank"
                           class="btn btn-view">
                            VIEW
                        </a>

                        <a href="{{ route('secure.download', ['path' => $file['path'], 'name' => $file['original_name']]) }}"
                           class="btn btn-download">
                            DOWNLOAD
                        </a>

                        @if($isEditable)
                            <button type="button"
                                    class="btn btn-edit"
                                    onclick="toggleEdit('optional-{{ $s->id }}-{{ $key }}')">
                                EDIT
                            </button>
                        @endif

                    </div>
                </div>

                {{-- Upload optional --}}
                @if($isEditable)
                    <div id="optional-{{ $s->id }}-{{ $key }}" style="display:none; margin-top:15px;">
                        <form method="POST"
                              action="{{ route('head.submissions.updateDocuments', $s->id) }}"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="replace_optional[{{ $key }}]" required class="upload-input">
                            <button type="submit" class="btn btn-save" style="margin-top:10px;">
                                Save
                            </button>
                        </form>
                    </div>
                @endif

                {{-- Checkbox --}}
                <div style="display:flex; align-items:center; gap:8px; margin-top:12px;">
                    <input type="checkbox"
                           class="verify-check verify-group-{{ $s->id }}"
                           {{ $isEditable ? '' : 'disabled checked' }}>
                    <span style="font-size:12px; font-weight:600;">Checked</span>
                </div>

            </div>
        @empty
            <p style="font-size:12px; color:gray;">No supporting documents uploaded.</p>
        @endforelse

        {{-- ================= REVIEW ================= --}}
        <div class="review-box">

            @if($isEditable)
                <h4 style="font-weight:bold; margin-bottom:10px;">
                    Submit Final Decision
                </h4>

                <form class="final-decision-form" method="POST" action="{{ route('head.submissions.approve', $s->id) }}">
                    @csrf
                    <textarea name="public_comment"
                              placeholder="Comment for Academician ..."></textarea>

                    <textarea name="internal_comment"
                              placeholder="Instruction for Admin..."
                              style="margin-top:10px;"></textarea>

                    <button class="btn btn-approve" type="submit"
                            style="width:100%; margin-top:10px;">
                        Recommend
                    </button>
                </form>

                <div style="margin-top:15px; padding-top:15px; border-top:1px dashed #ccc;">
                    <form class="final-decision-form" method="POST" action="{{ route('head.submissions.reject', $s->id) }}">
                        @csrf

                        <textarea name="public_comment" required
                                  placeholder="Reason for rejection ..."></textarea>

                        <textarea name="internal_comment"
                                  placeholder="Comment to R&I Staff..."
                                  style="margin-top:10px;"></textarea>

                        <button class="btn btn-reject" type="submit"
                                style="width:100%; margin-top:10px;">
                            Reject
                        </button>
                    </form>
                </div>

            @else
                <div style="background:#eef2ff; padding: 15px; border-radius: 8px;">
                    <p><strong>Decision Date:</strong> {{ $s->head_reviewed_at }}</p>
                    <p><strong>Comment:</strong> {{ $s->head_public_comment ?? '-' }}</p>

                    @if(auth()->user()->role === 'admin')
                        <p><strong>Instruction from Head:</strong> {{ $s->head_internal_comment ?? '-' }}</p>
                    @endif

                    <p style="color:red; font-size:11px; margin-top:10px;">
                        <em>Decision is finalized (READ ONLY)</em>
                    </p>
                </div>
            @endif

        </div>

    </div>
</div>
@endforeach

{{-- ================= SCRIPTS ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// ================= UI FUNCTIONS =================
function toggleChartPanel(){
    const el = document.getElementById('chartPanel');
    if(el){
        el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'block' : 'none';
    }
}


document.querySelectorAll(".final-decision-form").forEach(function(form) {

    form.addEventListener("submit", function (e) {

        let modal = form.closest(".modal");
        let checkboxes = modal.querySelectorAll(".verify-check:not([disabled])");

        let allChecked = true;

        checkboxes.forEach(cb => {
            if (!cb.checked) allChecked = false;
        });

        if (!allChecked) {
            e.preventDefault();
            alert("Please tick all checkboxes before submitting.");
        }

    });

});


function openModal(id) {
    const modal = document.getElementById('modal-' + id);
    if(modal){
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(id) {
    const modal = document.getElementById('modal-' + id);
    if(modal){
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

function toggleEdit(id) {
    const box = document.getElementById(id);
    if(box){
        box.style.display = (box.style.display === 'none' || box.style.display === '') ? 'block' : 'none';
    }
}


// ================= MAIN SCRIPT =================
document.addEventListener("DOMContentLoaded", function() {

    // ================= FORM VALIDATION =================
    const forms = [
        ...document.querySelectorAll('[id^="approveForm-"]'),
        ...document.querySelectorAll('[id^="rejectForm-"]')
    ];

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const id = this.id.split('-')[1];
            const checks = document.querySelectorAll('.verify-group-' + id);
            let allChecked = true;

            checks.forEach(c => {
                if(!c.checked) allChecked = false;
            });

            if(!allChecked) {
                e.preventDefault();
                alert('Please check all documents before proceeding.');
            }
        });
    });


    // ================= FILTER MAP =================
    const adminTypesMap = {
        1:[{id:1,name:'Funding'},{id:2,name:'Reward'}],
        2:[{id:3,name:'General'},{id:4,name:'Purchase'},{id:5,name:'GRA/RA'},{id:8,name:'Virement'}],
        3:[{id:6,name:'Local'},{id:7,name:'Overseas'}]
    };

    const cat = document.getElementById('cat_filter');
    const type = document.getElementById('type_filter');

    function updateTypeOptions(){
        if(!type) return;

        type.innerHTML = '<option value="">Select Type</option>';

        (adminTypesMap[cat?.value] || []).forEach(item => {
            let opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.name;
            type.appendChild(opt);
        });
    }

    if(cat){
        cat.addEventListener('change', updateTypeOptions);
        updateTypeOptions();
    }


    // ================= CHARTS =================
   const statusCanvas = document.getElementById('statusChart');

if (statusCanvas) {
    new Chart(statusCanvas, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Recommended', 'Rejected'],
            datasets: [{
                data: [{{ $pendingHead }}, {{ $recommended }}, {{ $rejected }}],

                // COLORS HERE
                backgroundColor: [
                    '#1e3a8a', // Pending (dark blue)
                    '#16a34a', // Recommended (green)
                    '#dc2626'  // Rejected (red)
                ],

                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false
        }
    });
}

   const trendCanvas = document.getElementById('trendChart');

if (trendCanvas) {
    new Chart(trendCanvas, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},

            datasets: [
                {
                    label: 'Pending',
                    data: {!! json_encode($pendingSeries) !!},

                    //  DARK BLUE
                    borderColor: '#1e3a8a',
                    backgroundColor: 'rgba(30, 58, 138, 0.1)',
                    tension: 0.3
                },
                {
                    label: 'Recommended',
                    data: {!! json_encode($recommendedSeries) !!},

                    //  GREEN
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22, 163, 74, 0.1)',
                    tension: 0.3
                },
                {
                    label: 'Rejected',
                    data: {!! json_encode($rejectedSeries) !!},

                    //  RED
                    borderColor: '#dc2626',
                    backgroundColor: 'rgba(220, 38, 38, 0.1)',
                    tension: 0.3
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true
        }
    });
}

});
function downloadReport() {

    const statusCanvas = document.getElementById('statusChart');
    const trendCanvas = document.getElementById('trendChart');

    const statusImage = statusCanvas.toDataURL("image/png");
    const trendImage = trendCanvas.toDataURL("image/png");

    fetch("{{ route('head.chart.save') }}", {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            statusImage,
            trendImage
        })
    })
    .then(() => {
        window.location.href = "{{ route('head.submissions.report', request()->query()) }}";
    });
}
</script>

</x-app-layout>
