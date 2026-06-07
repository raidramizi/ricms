<x-app-layout>
    <x-slot name="title">
    Admin Dashboard
</x-slot>

<style>

.table-container { width: 100%; background: #fff; border-radius: 10px; padding: 20px; overflow-x: auto; border: 1px solid #eee; }
.custom-table { width: 100%; border-collapse: collapse; min-width: 900px; }
.custom-table th { text-align: left; background: #f8f9fa; padding: 14px; font-weight: 600; border-bottom: 2px solid #eee; }
.custom-table td { padding: 12px; border-bottom: 1px solid #f1f1f1; vertical-align: middle; }
.custom-table tr:hover { background: #fafafa; }
.btn { padding: 6px 12px; font-size: 12px; border-radius: 5px; color: white; border: none; cursor: pointer; display: inline-block; text-decoration: none; margin: 2px; transition: 0.3s; }
.btn-approve { background: #28a745; }
.btn-reject { background: #dc3545; }
.btn-view { background: #3490dc; }
.badge { padding: 4px 8px; border-radius: 5px; color: white; font-size: 12px; font-weight: 500; display: inline-block; }
.badge-green { background: #28a745; }
.badge-darkgreen { background: #075218; }
.badge-red { background: #dc3545; }
.badge-yellow { background: #f39c12; }
.badge-purple { background: #6c5ce7; }
.filter-bar { background: #fff; padding: 15px; border-radius: 10px; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end; border: 1px solid #eee; }
.filter-input { border: 1px solid #ddd; padding: 8px; border-radius: 5px; font-size: 14px; min-width: 180px; }
.status-time { font-size: 10px; color: #718096; margin-top: 5px; font-weight: 500; display: block; }

/* MODAL STYLES */
.modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; padding: 20px; }
.modal-content {
    background: white;
    width: 700px;
    max-width: 100%;
    margin: 20px auto;
    padding: 25px;
    border-radius: 12px;
    position: relative;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
}
.modal-body { overflow-y: auto; padding-right: 15px; flex-grow: 1; }
.file-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding: 12px; border: 1px solid #edf2f7; border-radius: 8px; }
.status-box { padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid; }
.feedback-box { background: #f7fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; margin-top: 10px; }
hr { margin: 20px 0; border: 0; border-top: 1px solid #eee; }
h4 { font-weight: bold; margin-bottom: 12px; color: #2d3748; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; }
/* ================= DASHBOARD ANALYTICS ONLY (SAFE ADD-ON) ================= */

/* container grid for KPI cards */
.dashboard-grid{
    display:grid;
    grid-template-columns: repeat(6, 1fr);
    gap:15px;
    margin-bottom:20px;
}
/* KPI card base */
.card{
    background:#fff;
    border:1px solid #eee;
    border-radius:12px;
    padding:15px;
    box-shadow:0 2px 6px rgba(0,0,0,0.04);
    transition:0.2s;
}
.card:hover{
    transform:translateY(-2px);
}
/* card text */
.card h3{
    font-size:12px;
    color:#666;
    margin-bottom:5px;
}
.card .value{
    font-size:22px;
    font-weight:bold;
}
/* colored indicators */
.card.pending{border-left:5px solid #f39c12;}
.card.verified{border-left:5px solid #2553b0;}
.card.headreview{border-left:5px solid #524aab;}
.card.rejected{border-left:5px solid #dc3545;}
.card.recommended{border-left:5px solid #28a745;}
.card.approved{border-left:5px solid #175e22;}
.card.total{border-left:5px solid #103b5f;}

/* collapsible analytics panel */
.panel{
    background:#fff;
    border:1px solid #eee;
    border-radius:10px;
    margin-bottom:20px;
}
.panel-header{
    padding:12px 15px;
    font-weight:bold;
    cursor:pointer;
    display:flex;
    justify-content:space-between;
    user-select:none;
}
.panel-body{
    padding:15px;
    display:none;
}
.panel.active .panel-body{
    display:block;
}
/* filter inputs (reuse safely) */
.analytics-filter{
    padding:8px;
    border:1px solid #ddd;
    border-radius:6px;
}
</style>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin Dashboard</h2>
</x-slot>

<div class="p-6">
    {{-- ================= ANALYTICS DASHBOARD ================= --}}
<div class="panel active" id="analyticsPanel">

    <div class="panel-header" onclick="togglePanel('analyticsPanel')">
        📊 Submission Analytics
        <span>▼</span>
    </div>

    <div class="panel-body">
        {{-- ================= GRAPH FILTERS ================= --}}
<form id="graphFilterForm" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:15px;">
    <div>From:</div>
    <input type="date" name="from" id="fromDate" class="filter-input">
    <div>To:</div>
    <input type="date" name="to" id="toDate" class="filter-input">

    <select name="category_id" id="graphCategory" class="filter-input">
        <option value="">All Category</option>
        <option value="1">Publication</option>
        <option value="2">Grant</option>
        <option value="3">Conference</option>
    </select>


    <select name="type_id" id="graphType" class="filter-input">
        <option value="">All Type</option>
    </select>
    <select name="status" id="graphStatus" class="filter-input">
    <option value="">All Status</option>
    <option value="pending_admin">Pending</option>
    <option value="verified_admin">Verified</option>
    <option value="sent_to_head">Under Head Review</option>
    <option value="approved_head">Recommended</option>
    <option value="approved">Approved</option>
    <option value="rejected_admin">Rejected (Admin)</option>
    <option value="rejected_head">Rejected (Head)</option>
</select>

    <button type="button" onclick="loadGraph()" class="btn btn-view">
        Apply
    </button>

</form>

       {{-- CHART --}}
<div style="height:300px; position:relative;">
    <canvas id="statusChart" style="height:300px;"></canvas>
</div>


    </div>
</div>

<div>     {{-- KPI BOXES --}}
        <div class="dashboard-grid">

            <div class="card pending">
                <h3>Pending</h3>
                <div class="value">{{ $pendingCount ?? 0 }}</div>
            </div>

    <div class="card verified">
        <h3>Verified</h3>
        <div class="value">{{ $verifiedCount ?? 0 }}</div>
    </div>


    <div class="card headreview">
        <h3>Under Head Review</h3>
        <div class="value">{{ $underHeadReviewCount ?? 0 }}</div>
    </div>


            <div class="card rejected">
                <h3>Rejected</h3>
                <div class="value">{{ $rejectedCount ?? 0 }}</div>
            </div>

            <div class="card recommended">
                <h3>Recommended</h3>
                <div class="value">{{ $recommendedCount ?? 0 }}</div>
            </div>
           <div class="card approved">
         <h3>Approved</h3>
         <div class="value">{{ $approvedCount ?? 0 }}</div>
        </div>

            <div class="card total">
                <h3>Total</h3>
                <div class="value">{{ $totalCount ?? 0 }}</div>
            </div>

        </div>
</div>
    {{-- Filter Bar --}}
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

        <option value="pending_admin" {{ request('status') == 'pending_admin' ? 'selected' : '' }}>
            Pending
        </option>

        <option value="verified_admin" {{ request('status') == 'verified_admin' ? 'selected' : '' }}>
            Verified
        </option>

        <option value="sent_to_head" {{ request('status') == 'sent_to_head' ? 'selected' : '' }}>
            Under Head Review
        </option>

        <option value="approved_head" {{ request('status') == 'approved_head' ? 'selected' : '' }}>
            Recommended
        </option>

        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
            Approved
        </option>

        <option value="rejected_admin" {{ request('status') == 'rejected_admin' ? 'selected' : '' }}>
            Rejected (Admin)
        </option>

        <option value="rejected_head" {{ request('status') == 'rejected_head' ? 'selected' : '' }}>
            Rejected (Head)
        </option>
    </select>
</div>

        <button type="submit" class="btn" style="background:#4a5568;">Apply</button>
        <a href="{{ url()->current() }}" class="btn" style="background:#edf2f7; color:#4a5568;">Reset</a>
    </form>

    <div class="table-container shadow-sm">
        <table class="custom-table">
            <thead>
    <tr>
        <th>No</th>
        <th>Staff</th>
        <th>Type</th>
        <th>Category</th>
        <th>Documents</th>
        <th>Status</th>
        <th>Action</th>
        <th>Head Comment</th>
    </tr>
</thead>
            <tbody>
                 @forelse($submissions as $index => $s)
<tr>
                   <td>
                     {{ $submissions->firstItem() + $index }}
                    </td>
                    <td>
                        <strong>{{ $s->user->name ?? 'Unknown Staff' }}</strong>
                        <div style="font-size:11px;color:gray;">Submitted: {{ $s->created_at?->format('d M Y') }}</div>
                    </td>
                    <td>{{ $s->type->name ?? '-' }}</td>
                    <td>{{ $s->category->name ?? '-' }}</td>
                    <td><button class="btn btn-view" onclick="openModal({{ $s->id }})">Open</button></td>
             <td>
    @if($s->status == 'approved')
        <span class="badge badge-darkgreen">
            Approved
        </span>

    @elseif($s->status == 'approved_head')
        <span class="badge badge-green">
            Recommended
        </span>

    @elseif($s->status == 'sent_to_head')
        <span class="badge badge-purple">
            Under Head Review
        </span>

    @elseif($s->status == 'verified_admin')
        <span class="badge badge-green">
            Verified
        </span>

    @elseif(in_array($s->status, ['rejected_admin', 'rejected_head']))
        <span class="badge badge-red">
            Rejected
        </span>

    @else
        <span class="badge badge-yellow">
            Pending
        </span>
    @endif

    @if($s->updated_at && $s->status)
        <div class="status-time">
            Updated: {{ $s->updated_at->format('d M, h:i A') }}
        </div>
    @endif
</td>
                    <td>
                        @if($s->status == 'verified_admin')
                        <form method="POST" action="{{ route('admin.submissions.submitHead',$s->id) }}">
                            @csrf
                            <button class="btn" style="background:#3319f5;">Submit to Head</button>
                        </form>
                        @elseif($s->status == 'sent_to_head')
                             <span style="font-size:11px; color:#6c5ce7; font-weight:bold;">Awaiting Head Review</span>
                        @elseif($s->status == 'rejected_head')
                             <span style="font-size:11px; color:#f21616; font-weight:bold;">Rejected</span>
                        @elseif($s->status == 'rejected_admin')
                             <span style="font-size:11px; color:#f21616; font-weight:bold;">Rejected</span>
                        @elseif($s->status == 'approved_head')
                             <span style="font-size:11px; color:#28a745; font-weight:bold;">Recommended</span>
                        @elseif($s->status == 'approved')
                             <span style="font-size:11px; color:#00751c; font-weight:bold;">Approved</span>
                        @else
                             <span style="font-size:11px; color:#a0aec0;">Application not verified</span>
                        @endif
                    </td>
                     <td style="padding:14px;">
                     <div style="background:#e0f2fe; padding:6px 10px; border-radius:6px; font-size:12px;">
                    {{ Str::limit($s->head_internal_comment ?? 'No internal instruction', 40) }}
                     </div>
                       </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:20px; color:gray;">No submissions found for the selected filters.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">
            {{ $submissions->links() }}
        </div>
    </div>
</div>
<div>
<button onclick="downloadReport()" class="btn btn-view" style="background:#2d3748;">
    Download Report (PDF)
</button></div></br>

{{-- MODAL --}}
@foreach($submissions as $s)
<div id="modal-{{ $s->id }}" class="modal">
    <div class="modal-content">
        <span onclick="closeModal({{ $s->id }})" style="position:absolute; right:20px; top:15px; cursor:pointer; font-size:24px;">&times;</span>
        <h3 style="font-size:1.5rem; font-weight:bold; margin-bottom:5px;">Document Verification</h3>

        <div class="modal-body">
            @php
                $evidence = is_string($s->evidence_files) ? json_decode($s->evidence_files,true) : $s->evidence_files;
                $optional = is_string($s->evidence_optional) ? json_decode($s->evidence_optional,true) : $s->evidence_optional;
                $isPending = ($s->status == 'pending_admin' || $s->status == null);
            @endphp

            <div class="status-box" style="{{ $isPending ? 'background:#fffaf0; border-color:#feebc8; color:#9c4221;' : 'background:#f0fff4; border-color:#c6f6d5; color:#276749;' }}">
                <strong>Current Status:</strong>
                @if($s->status == 'verified_admin') Verified
                @elseif($s->status == 'sent_to_head') Under Head Review
                @elseif($s->status == 'approved_head') Recommended by Head
                @elseif($s->status == 'approved') Approved
                @elseif($s->status == 'rejected_head') Rejected
                @elseif($s->status == 'rejected_admin') Rejected
                @else Pending @endif

                @if(!$isPending)
                    <div style="font-size: 11px; opacity: 0.8; margin-top: 4px;">Last Activity: {{ $s->updated_at?->format('d M Y, h:i A') }}</div>
                @endif
            </div>

            @if(!$isPending && $s->admin_comment)
            <div class="feedback-box">
                <h4>Review History</h4>
                <p style="margin-bottom:8px;">
                    <span style="font-weight:bold; color:#3490dc;">Admin Comment:</span> {{ $s->admin_comment }}
                </p>
            </div>
            <hr>
            @endif

            @if($isPending)
                <p style="font-size:12px; color:#e53e3e; margin-bottom:15px;">* Verify all documents before processing.</p>
                <form method="POST" action="{{ route('admin.submissions.processFiles',$s->id) }}">
                    @csrf
            @endif

                <h4>Main Form File</h4>
                @if($s->form_file)
                <div class="file-row">
                    <span>{{ $s->form_file_name }}</span>
                    <div style="display:flex; gap:15px; align-items:center;">
                        <a class="btn btn-view" target="_blank" href="{{ asset('storage/'.$s->form_file) }}">Open</a>
                        @if($isPending) <input type="checkbox" name="checked_files[]" required> @endif
                    </div>
                </div>
                @endif

                <hr>

                <h4>Evidence Files</h4>
                @forelse($evidence ?? [] as $key=>$file)
                <div class="file-row">
                    <span> {{ $file['original_name'] }}</span>
                    <div style="display:flex; gap:15px; align-items:center;">
                        <a class="btn btn-view" target="_blank" href="{{ asset('storage/'.$file['path']) }}">Open</a>
                        @if($isPending) <input type="checkbox" name="checked_files[]" required> @endif
                    </div>
                </div>
                @empty <p style="font-size:12px; color:gray; padding-bottom:10px;">No evidence files.</p> @endforelse

                <hr>

                <h4>Supporting Documents</h4>
                @forelse($optional ?? [] as $file)
                <div class="file-row">
                    <span>{{ $file['original_name'] }}</span>
                    <div style="display:flex; gap:15px; align-items:center;">
                        <a class="btn btn-view" target="_blank" href="{{ asset('storage/'.$file['path']) }}">Open</a>
                        @if($isPending) <input type="checkbox" name="checked_files[]" required> @endif
                    </div>
                </div>
                @empty <p style="font-size:12px; color:gray; padding-bottom:10px;">No supporting documents.</p> @endforelse

              @if($isPending)
<hr>
<h4>Admin Comment For Academician:</h4>

<textarea id="admin_comment" name="admin_comment" rows="4"
    style="width:100%; border:1px solid #ccc; border-radius:6px; padding:10px;"
    placeholder="Enter your comment here..."></textarea>

<div style="margin-top:20px; display:flex; gap:10px; padding-bottom:20px;">

    <button type="submit" name="action" value="approve"
        onclick="setOptional()"
        class="btn btn-approve" style="flex:2;">
        VERIFIED
    </button>

    <button type="submit" name="action" value="reject"
        onclick="setRequired()"
        class="btn btn-reject" style="flex:1;">
        REJECTED
    </button>

</div>

<script>
function setRequired() {
    document.getElementById('admin_comment').required = true;
}

function setOptional() {
    document.getElementById('admin_comment').required = false;
}
</script>

</form>
@endif

        </div>
    </div>
</div>
@endforeach
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. DATA MAP: Use numeric IDs to match your Database
    const adminTypesMap = {
        "1": [{id: 1, name: 'Funding'}, {id: 2, name: 'Reward'}],
        "2": [{id: 3, name: 'General'}, {id: 4, name: 'Purchase'}, {id: 5, name: 'GRA/RA'}, {id: 8, name: 'Virement'}],
        "3": [{id: 6, name: 'Local'}, {id: 7, name: 'Overseas'}]
    };
    function syncGraphType() {

    const category = document.getElementById('graphCategory');
    const type = document.getElementById('graphType');

    function updateGraphTypes() {

        const selected = category.value;

        type.innerHTML = '<option value="">All Type</option>';

        if (adminTypesMap[selected]) {
            adminTypesMap[selected].forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.name;
                type.appendChild(opt);
            });
        }

        // auto reload graph when category changes
        loadGraph();
    }

    category.addEventListener('change', updateGraphTypes);
    type.addEventListener('change', loadGraph);

    updateGraphTypes(); // initial load
}

    // 2. FILTER LOGIC
    function initFilters(catId, typeId, currentTypeId) {
        const catSelect = document.getElementById(catId);
        const typeSelect = document.getElementById(typeId);

        function update() {
            const selectedCat = catSelect.value;
            // Clear current options
            typeSelect.innerHTML = '<option value="">Select Type</option>';

            if (adminTypesMap[selectedCat]) {
                adminTypesMap[selectedCat].forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.name;

                    // Match the ID from URL (currentTypeId) to keep selection active
                    if (String(item.id) === String(currentTypeId)) {
                        opt.selected = true;
                    }
                    typeSelect.appendChild(opt);
                });
            }
        }

        catSelect.addEventListener('change', update);
        update(); // Call once on load to populate if category is already selected
    }

    // Initialize with IDs from HTML and current URL state
    initFilters('cat_filter', 'type_filter', "{{ request('type_id') }}");

    // Modal Handlers
    function openModal(id){
        document.getElementById('modal-'+id).style.display='block';
        document.body.style.overflow='hidden';
    }
    function closeModal(id){
        document.getElementById('modal-'+id).style.display='none';
        document.body.style.overflow='auto';
    }
    window.onclick = function(event) {
        if (event.target.className === 'modal') {
            event.target.style.display = "none";
            document.body.style.overflow='auto';
        }
    }
    // ================= COLLAPSIBLE PANEL =================
function togglePanel(id){
    document.getElementById(id).classList.toggle('active');
}

// ================= CHART =================
let chartInstance;

// Load graph on page load
document.addEventListener("DOMContentLoaded", function () {
    syncGraphType();
    loadGraph();
});

// MAIN FUNCTION


function loadGraph() {

    const from = document.getElementById('fromDate').value;
    const to = document.getElementById('toDate').value;
    const category = document.getElementById('graphCategory').value;
    const type = document.getElementById('graphType').value;
    const status = document.getElementById('graphStatus').value;

    const url = "{{ route('admin.submissions.graph') }}";

    fetch(`${url}?from=${from}&to=${to}&category_id=${category}&type_id=${type}&status=${status}`)
        .then(res => res.json())
        .then(data => {

            const canvas = document.getElementById('statusChart');

            if (!canvas) {
                console.error("Chart canvas not found");
                return;
            }

            const ctx = canvas.getContext('2d');

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Pending',
                            data: data.pending,
                            borderColor: '#f39c12',
                            tension: 0.4
                        },
                        {
                            label: 'Verified',
                            data: data.verified,
                            borderColor: '#3490dc',
                            tension: 0.4
                        },
                        {
                            label: 'Under Head Review',
                            data: data.underHead,
                            borderColor: '#6c5ce7',
                            tension: 0.4
                        },
                        {
                            label: 'Recommended',
                            data: data.recommended,
                            borderColor: '#28a745',
                            tension: 0.4
                        },
                        {
                            label: 'Approved',
                            data: data.approved,
                            borderColor: '#155724',
                            tension: 0.4
                        },
                        {
                            label: 'Rejected',
                            data: data.rejected,
                            borderColor: '#dc3545',
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        })
        .catch(err => {
            console.error("Graph load failed:", err);
        });
}
function togglePanel(id){
    document.getElementById(id).classList.toggle('active');

    setTimeout(() => {
        if (typeof loadGraph === 'function') {
            loadGraph();
        }
    }, 300);
}
function downloadReport() {

    const canvas = document.getElementById('statusChart');
    const image = canvas.toDataURL("image/png");

    fetch("{{ route('admin.chart.save') }}", {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ image })
    })
    .then(() => {
        window.location.href = "{{ route('admin.submissions.report', request()->query()) }}";
    });
}
</script>




</x-app-layout>
