<x-app-layout>
    <x-slot name="title">
    My Application
</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Application Status') }}
        </h2>
    </x-slot>

    <div class="cl-wrap">

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="cl-alert">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- FILTER --}}
        <div class="cl-filter">
            <p class="cl-filter-label">Filter Submissions</p>
            <form action="{{ route('claim.status') }}" method="GET" class="cl-filter-row">
                <div class="cl-field">
                    <label>Category</label>
                    <select name="category_id">
                        <option value="">All Categories</option>
                        @foreach(App\Models\Category::all() as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="cl-field">
                    <label>Type</label>
                    <select name="type_id">
                        <option value="">All Types</option>
                        @foreach(App\Models\Type::all() as $type)
                            <option value="{{ $type->id }}" {{ request('type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="cl-filter-actions">
                    <button type="submit" class="cl-btn cl-btn-primary">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M3 4h18M7 12h10M11 20h2"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('claim.status') }}" class="cl-btn cl-btn-ghost">Reset</a>
                </div>
            </form>
        </div>

        {{-- HISTORY HEADING --}}
        <div class="cl-section-head">
            <h2>Submission History</h2>
            <span class="cl-count">{{ $data->total() }} record(s)</span>
        </div>

        {{-- CARDS --}}
        @forelse($data as $s)
            @php
                $status = $s->status ?? 'pending';

                $accent = match($status) {
                    'pending_admin'                    => '#f59e0b',
                    'verified_admin'                   => '#3b82f6',
                    'sent_to_head'                     => '#8b5cf6',
                    'approved_head'                    => '#10b981',
                    'approved'                         => '#16a34a',
                    'rejected_admin', 'rejected_head'  => '#ef4444',
                    default                            => '#f59e0b',
                };

                $badgeCls = match($status) {
                    'pending_admin'                    => 'bdg-amber',
                    'verified_admin'                   => 'bdg-blue',
                    'sent_to_head'                     => 'bdg-purple',
                    'approved_head'                    => 'bdg-teal',
                    'approved'                         => 'bdg-green',
                    'rejected_admin', 'rejected_head'  => 'bdg-red',
                    default                            => 'bdg-amber',
                };

                $statusLabel = match($status) {
                    'pending_admin'  => 'Pending',
                    'verified_admin' => 'Verified',
                    'sent_to_head'   => 'Under Review',
                    'approved_head'  => 'Recommended',
                    'approved'       => 'Approved',
                    'rejected_admin' => 'Rejected',
                    'rejected_head'  => 'Rejected',
                    default          => 'Pending',
                };

                $steps = [
                    'pending_admin'  => 'Submitted',
                    'verified_admin' => 'Verified',
                    'sent_to_head'   => 'Under Review',
                    'approved_head'  => 'Recommended',
                    'approved'       => 'Approved',
                ];

                $current = $s->status;
                $keys    = array_keys($steps);
                $stopAt  = null;
                if ($current === 'rejected_admin') $stopAt = 'pending_admin';
                if ($current === 'rejected_head')  $stopAt = 'sent_to_head';
            @endphp

            <div class="cl-card" style="--accent: {{ $accent }}">

                {{-- CARD HEADER --}}
                <div class="cl-card-head">
                    <div class="cl-card-title">
                        <h3>{{ $s->category->name ?? 'N/A' }}</h3>
                        <p>{{ $s->type->name ?? 'N/A' }}</p>
                    </div>
                    <span class="bdg {{ $badgeCls }}">{{ $statusLabel }}</span>
                </div>

                {{-- TIMELINE --}}
                <div class="cl-timeline">
                    @foreach($steps as $key => $label)
                        @php
                            $stepState = 'pending';
                            if ($stopAt) {
                                if (array_search($key, $keys) <= array_search($stopAt, $keys)) {
                                    $stepState = 'reject';
                                }
                            } else {
                                if (array_search($key, $keys) <= array_search($current, $keys)) {
                                    $stepState = 'done';
                                }
                            }
                        @endphp
                        <div class="cl-step {{ $stepState }}">
                            <div class="cl-dot">
                                @if($stepState === 'done')
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#fff" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                @elseif($stepState === 'reject')
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path stroke="#fff" stroke-width="3" d="M6 6l12 12M18 6L6 18"/></svg>
                                @endif
                            </div>
                            <p>{{ $label }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- DATES GRID --}}
                <div class="cl-dates">
                    <div>
                        <p><span>Submitted</span> {{ $s->created_at->format('d M Y, h:i A') }}</p>
                        @if($s->verified_at)
                            <p><span>Verified</span> {{ \Carbon\Carbon::parse($s->verified_at)->format('d M Y, h:i A') }}</p>
                        @endif
                        @if($s->rejected_at)
                            <p><span>Rejected</span> {{ \Carbon\Carbon::parse($s->rejected_at)->format('d M Y, h:i A') }}</p>
                        @endif
                    </div>
                    <div>
                        @if($s->sent_to_head_at)
                            <p><span>Sent to Head</span> {{ \Carbon\Carbon::parse($s->sent_to_head_at)->format('d M Y, h:i A') }}</p>
                        @endif
                        @if($s->head_reviewed_at)
                            <p><span>Head Reviewed</span> {{ \Carbon\Carbon::parse($s->head_reviewed_at)->format('d M Y, h:i A') }}</p>
                        @endif
                        @if(optional($s->completion)->done_at)
                            <p><span>Approved</span> {{ \Carbon\Carbon::parse($s->completion->done_at)->format('d M Y, h:i A') }}</p>
                        @endif
                    </div>
                </div>

{{-- CARD FOOTER --}}
<div class="cl-card-foot">

    <div class="flex gap-2 flex-wrap">

        {{-- Submission Documents --}}
        <button type="button"
                onclick="openModal('modal-{{ $s->id }}')"
                class="cl-btn cl-btn-docs">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-width="2"
                      d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                <polyline stroke="currentColor" stroke-width="2"
                          points="14 2 14 8 20 8"/>
            </svg>
            Submission Documents
        </button>

        {{-- Approval Attachment --}}
        @if($s->approval && $s->approval->proof_file)
            <a class="cl-btn cl-btn-dl"
               href="{{ route('secure.download', ['path' => $s->approval->proof_file, 'name' => $s->approval->proof_name]) }}">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-width="2"
                          d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                </svg>
                Approval Attachment
            </a>
        @endif

    </div>

</div>

{{-- ================= COMMENTS (OUTSIDE FOOTER) ================= --}}
@php
    $adminRemark = $s->admin_comment ?? $s->comment ?? $s->admin_remark;
    $hasAnyComment = $adminRemark || $s->head_public_comment || $s->head_internal_comment;
@endphp

@if($hasAnyComment)
    <div x-data="{ open: false }" class="mt-3">

        {{-- Toggle Button --}}
        <button type="button"
                @click="open = !open"
                class="text-xs font-semibold text-gray-500 hover:text-gray-700 flex items-center gap-1">
            <span>Reviewer Comments</span>

            <svg :class="open ? 'rotate-180' : ''"
                 class="transition-transform duration-200"
                 width="14" height="14" viewBox="0 0 24 24" fill="none">
                <path d="M6 9l6 6 6-6"
                      stroke="currentColor"
                      stroke-width="2"/>
            </svg>
        </button>

        {{-- Collapsible Content --}}
        <div x-show="open" x-collapse
             class="mt-3 p-4 bg-gray-50 rounded-lg border border-gray-100">

            {{-- ADMIN COMMENT --}}
            @if($adminRemark)
                <div class="mb-3">
                    <span class="text-[10px] font-bold bg-blue-100 text-blue-700 px-2 py-0.5 rounded">
                        ADMIN
                    </span>
                    <p class="text-gray-700 mt-1">{{ $adminRemark }}</p>
                </div>
            @endif

            {{-- HEAD PUBLIC --}}
            @if($s->head_public_comment)
                <div class="mb-3">
                    <span class="text-[10px] font-bold bg-blue-100 text-blue-700 px-2 py-0.5 rounded">
                        HEAD (OFFICIAL)
                    </span>
                    <p class="text-gray-700 mt-1">{{ $s->head_public_comment }}</p>
                </div>
            @endif

            {{-- HEAD INTERNAL (ADMIN ONLY) --}}
            @if(auth()->check() && auth()->user()->role === 'admin' && $s->head_internal_comment)
                <div class="mb-3">
                    <span class="text-[10px] font-bold bg-red-100 text-red-700 px-2 py-0.5 rounded">
                        HEAD (INTERNAL)
                    </span>
                    <p class="text-gray-700 mt-1">{{ $s->head_internal_comment }}</p>
                </div>
            @endif

        </div>
    </div>
@endif

</div>



            {{-- ===== MODAL ===== --}}
            <div id="modal-{{ $s->id }}" class="cl-modal" aria-hidden="true">

                <div class="cl-modal-backdrop" onclick="closeModal('modal-{{ $s->id }}')"></div>

                <div class="cl-modal-box" role="dialog" aria-modal="true">

                    {{-- Modal Header --}}
                    <div class="cl-modal-hd">
                        <div class="cl-modal-hd-left">
                            <div class="cl-modal-icon">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline stroke="currentColor" stroke-width="2" points="14 2 14 8 20 8"/><line stroke="currentColor" stroke-width="2" x1="16" y1="13" x2="8" y2="13"/><line stroke="currentColor" stroke-width="2" x1="16" y1="17" x2="8" y2="17"/></svg>
                            </div>
                            <div>
                                <h3 class="cl-modal-title">Submission Documents</h3>
                                <p class="cl-modal-sub">{{ $s->category->name ?? '' }} &mdash; {{ $s->type->name ?? '' }}</p>
                            </div>
                        </div>
                        <button class="cl-modal-close" onclick="closeModal('modal-{{ $s->id }}')" aria-label="Close">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M18 6L6 18M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="cl-modal-body">

                        {{-- Status pill --}}
                        <div class="cl-modal-status-row">
                            <span class="bdg {{ $badgeCls }}">{{ $statusLabel }}</span>
                            <span class="cl-modal-updated">Updated {{ $s->updated_at?->format('d M Y, h:i A') }}</span>
                        </div>

                        {{-- Main Form --}}
                        @if($s->form_file)
                        <div class="cl-doc-section">
                            <p class="cl-doc-section-label">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24"><rect stroke="currentColor" stroke-width="2" x="3" y="3" width="18" height="18" rx="2"/><path stroke="currentColor" stroke-width="2" d="M3 9h18M9 21V9"/></svg>
                                Main Form
                            </p>
                            <div class="cl-doc-row">
                                <div class="cl-doc-info">
                                    <div class="cl-doc-thumb">
                                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path stroke="#6366f1" stroke-width="2" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline stroke="#6366f1" stroke-width="2" points="14 2 14 8 20 8"/></svg>
                                    </div>
                                    <span class="cl-doc-name">{{ $s->form_file_name }}</span>
                                </div>
                                <a target="_blank"
                                   href="{{ route('secure.download', ['path' => $s->form_file, 'name' => $s->form_file_name]) }}"
                                   class="cl-doc-btn">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
                                    Download
                                </a>
                            </div>
                        </div>
                        @endif

                        {{-- Evidence Files --}}
                        <div class="cl-doc-section">
                            <p class="cl-doc-section-label">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/></svg>
                                Evidence Files
                            </p>

@php
    $evidenceFiles = is_string($s->evidence_files)
        ? json_decode($s->evidence_files, true)
        : $s->evidence_files;

    $evidenceFiles = $evidenceFiles ?? [];
@endphp

@forelse($evidenceFiles as $file)

    @php
        if (is_array($file)) {
            $path = $file['path'] ?? '';
            $eName = $file['original_name'] ?? basename($path);
        } else {
            $path = $file;
            $eName = basename($file);
        }
    @endphp
                                <div class="cl-doc-row">
                                    <div class="cl-doc-info">
                                        <div class="cl-doc-thumb cl-doc-thumb-green">
                                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path stroke="#10b981" stroke-width="2" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline stroke="#10b981" stroke-width="2" points="14 2 14 8 20 8"/></svg>
                                        </div>
                                        <span class="cl-doc-name">{{ $eName }}</span>
                                    </div>
                                    <a target="_blank"
   href="{{ route('secure.download', ['path' => $path, 'name' => $eName]) }}"
   class="cl-doc-btn">
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
                                        Download
                                    </a>
                                </div>
                            @empty
                                <div class="cl-doc-empty">
                                    <svg width="28" height="28" fill="none" viewBox="0 0 24 24"><path stroke="#d1d5db" stroke-width="1.5" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline stroke="#d1d5db" stroke-width="1.5" points="14 2 14 8 20 8"/></svg>
                                    <p>No evidence files attached</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- Supporting Documents --}}
                        <div class="cl-doc-section">
                            <p class="cl-doc-section-label">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path stroke="currentColor" stroke-width="2" d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                                Supporting Documents
                            </p>
                           @php
    $evidenceOptional = $s->evidence_optional;

    if (is_string($evidenceOptional)) {
        $decoded = json_decode($evidenceOptional, true);

        // safety for double-encoded JSON
        if (is_string($decoded)) {
            $decoded = json_decode($decoded, true);
        }

        $evidenceOptional = $decoded;
    }

    $evidenceOptional = is_array($evidenceOptional) ? $evidenceOptional : [];
@endphp

@forelse($evidenceOptional as $file)

    @php
        if (is_array($file)) {
            $path = $file['path'] ?? '';
            $oName = $file['original_name'] ?? basename($path);
        } else {
            $path = $file;
            $oName = basename($file);
        }
    @endphp


                                <div class="cl-doc-row">
                                    <div class="cl-doc-info">
                                        <div class="cl-doc-thumb cl-doc-thumb-amber">
                                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path stroke="#f59e0b" stroke-width="2" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline stroke="#f59e0b" stroke-width="2" points="14 2 14 8 20 8"/></svg>
                                        </div>
                                        <span class="cl-doc-name">{{ $oName }}</span>
                                    </div>
                                    <a target="_blank"
                                       href="{{ route('secure.download', ['path' => $file['path'], 'name' => $oName]) }}"
                                       class="cl-doc-btn">
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
                                        Download
                                    </a>
                                </div>
                            @empty
                                <div class="cl-doc-empty">
                                    <svg width="28" height="28" fill="none" viewBox="0 0 24 24"><path stroke="#d1d5db" stroke-width="1.5" d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path stroke="#d1d5db" stroke-width="1.5" d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                                    <p>No supporting documents attached</p>
                                </div>
                            @endforelse
                        </div>

                    </div>

                    {{-- Modal Footer --}}
                    <div class="cl-modal-ft">
                        <p class="cl-modal-ft-note">Files open in a new tab. Contact admin if a file fails to load.</p>
                        <button onclick="closeModal('modal-{{ $s->id }}')" class="cl-btn cl-btn-primary">
                            Done
                        </button>
                    </div>

                </div>
            </div>
            {{-- ===== END MODAL ===== --}}

        @empty
            <div class="cl-empty">
                <svg width="48" height="48" fill="none" viewBox="0 0 24 24"><path stroke="#d1d5db" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p>No submissions found.</p>
                <small>Try adjusting your filters or submit a new claim.</small>
            </div>
        @endforelse

        <div class="cl-pagination">
            {{ $data->appends(request()->query())->links() }}
        </div>

    </div>

    {{-- ====== JS ====== --}}
    <script>
        function openModal(id) {
            const el = document.getElementById(id);
            el.classList.add('cl-modal-open');
            document.body.style.overflow = 'hidden';
        }
        function closeModal(id) {
            const el = document.getElementById(id);
            el.classList.remove('cl-modal-open');
            document.body.style.overflow = '';
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.cl-modal.cl-modal-open').forEach(m => {
                    m.classList.remove('cl-modal-open');
                    document.body.style.overflow = '';
                });
            }
        });
    </script>

    {{-- ====== STYLES ====== --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

        :root {
            --bg:        #f4f6fb;
            --surface:   #ffffff;
            --border:    #e8ecf3;
            --text:      #1a1d23;
            --muted:     #6b7280;
            --radius:    14px;
            --shadow:    0 2px 12px rgba(15,23,42,.07);
            --shadow-lg: 0 8px 32px rgba(15,23,42,.12);
        }

        * { box-sizing: border-box; }
        body, .cl-wrap * { font-family: 'DM Sans', sans-serif; }

        /* ── LAYOUT ── */
        .cl-wrap {
            max-width: 860px;
            margin: 0 auto;
            padding: 32px 20px 60px;
        }

        /* ── ALERT ── */
        .cl-alert {
            display: flex; align-items: center; gap: 8px;
            background: #ecfdf5; color: #065f46;
            border: 1px solid #a7f3d0;
            border-radius: var(--radius);
            padding: 12px 16px;
            font-size: 14px; font-weight: 500;
            margin-bottom: 24px;
        }

        /* ── FILTER ── */
        .cl-filter {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px 24px;
            box-shadow: var(--shadow);
            margin-bottom: 28px;
        }
        .cl-filter-label {
            font-size: 11px; font-weight: 700;
            letter-spacing: .08em; text-transform: uppercase;
            color: var(--muted); margin-bottom: 14px;
        }
        .cl-filter-row {
            display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end;
        }
        .cl-field { flex: 1; min-width: 180px; }
        .cl-field label {
            display: block; font-size: 12px; font-weight: 600;
            color: var(--muted); margin-bottom: 6px;
        }
        .cl-field select {
            width: 100%; padding: 10px 12px;
            border: 1px solid var(--border); border-radius: 10px;
            font-size: 14px; font-family: 'DM Sans', sans-serif;
            background: #fafbfd; color: var(--text);
            outline: none; transition: border-color .15s;
        }
        .cl-field select:focus { border-color: #6366f1; }
        .cl-filter-actions { display: flex; gap: 8px; align-items: flex-end; }

        /* ── BUTTONS ── */
        .cl-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 16px; border-radius: 10px;
            font-size: 13px; font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer; border: none; text-decoration: none;
            transition: all .15s;
        }
        .cl-btn-primary { background: #4f46e5; color: #fff; }
        .cl-btn-primary:hover { background: #4338ca; transform: translateY(-1px); }
        .cl-btn-ghost { background: #f3f4f6; color: #374151; }
        .cl-btn-ghost:hover { background: #e5e7eb; }
        .cl-btn-docs { background: #eef2ff; color: #4338ca; }
        .cl-btn-docs:hover { background: #e0e7ff; }
        .cl-btn-dl { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .cl-btn-dl:hover { background: #d1fae5; }

        /* ── SECTION HEAD ── */
        .cl-section-head {
            display: flex; align-items: baseline; gap: 12px; margin-bottom: 16px;
        }
        .cl-section-head h2 { font-size: 18px; font-weight: 700; color: var(--text); }
        .cl-count {
            font-size: 12px; font-weight: 500; color: var(--muted);
            background: #f3f4f6; padding: 3px 10px; border-radius: 999px;
        }

        /* ── CARD ── */
        .cl-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-left: 4px solid var(--accent, #e5e7eb);
            border-radius: var(--radius);
            padding: 22px 24px;
            margin-bottom: 16px;
            box-shadow: var(--shadow);
            transition: box-shadow .2s, transform .2s;
        }
        .cl-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-2px); }

        .cl-card-head {
            display: flex; justify-content: space-between;
            align-items: flex-start; margin-bottom: 18px;
        }
        .cl-card-title h3 { font-size: 16px; font-weight: 700; color: var(--text); margin: 0 0 3px; }
        .cl-card-title p  { font-size: 13px; color: var(--muted); font-weight: 500; }

        /* ── BADGES ── */
        .bdg {
            display: inline-flex; align-items: center;
            padding: 4px 12px; border-radius: 999px;
            font-size: 11px; font-weight: 700;
            letter-spacing: .04em; white-space: nowrap;
        }
        .bdg-amber  { background: #fff7ed; color: #b45309; }
        .bdg-blue   { background: #eff6ff; color: #1d4ed8; }
        .bdg-purple { background: #f5f3ff; color: #6d28d9; }
        .bdg-teal   { background: #f0fdfa; color: #0f766e; }
        .bdg-green  { background: #dcfce7; color: #15803d; }
        .bdg-red    { background: #fef2f2; color: #b91c1c; }

        /* ── TIMELINE ── */
        .cl-timeline {
            display: flex; align-items: flex-start;
            position: relative; margin: 0 0 20px;
        }
        .cl-timeline::before {
            content: ''; position: absolute;
            top: 14px; left: 14px; right: 14px;
            height: 2px; background: #e5e7eb; z-index: 0;
        }
        .cl-step { flex: 1; text-align: center; position: relative; z-index: 1; }
        .cl-dot {
            width: 28px; height: 28px; border-radius: 50%;
            margin: 0 auto 6px;
            border: 2px solid #fff; box-shadow: 0 0 0 2px #e5e7eb;
            background: #e5e7eb;
            display: flex; align-items: center; justify-content: center;
            transition: all .2s;
        }
        .cl-step.done .cl-dot   { background: #22c55e; box-shadow: 0 0 0 2px #86efac; }
        .cl-step.reject .cl-dot { background: #ef4444; box-shadow: 0 0 0 2px #fca5a5; }
        .cl-step p { font-size: 10px; font-weight: 600; color: #9ca3af; letter-spacing: .03em; }
        .cl-step.done p   { color: #16a34a; }
        .cl-step.reject p { color: #dc2626; }

        /* ── DATES ── */
        .cl-dates {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 8px 16px; font-size: 12.5px; color: var(--muted);
            padding: 14px 0 0; border-top: 1px solid #f1f5f9;
        }
        .cl-dates p { margin: 4px 0; }
        .cl-dates span { font-weight: 700; color: #374151; margin-right: 4px; }
        @media (max-width: 560px) { .cl-dates { grid-template-columns: 1fr; } }

        /* ── CARD FOOTER ── */
        .cl-card-foot {
            display: flex; gap: 10px; flex-wrap: wrap;
            margin-top: 18px; padding-top: 16px; border-top: 1px solid #f1f5f9;
        }

        /* ── EMPTY STATE ── */
        .cl-empty {
            text-align: center; padding: 60px 20px;
            background: var(--surface);
            border: 1px solid var(--border); border-radius: var(--radius);
        }
        .cl-empty svg { margin: 0 auto 16px; display: block; }
        .cl-empty p   { font-size: 15px; font-weight: 600; color: #6b7280; margin: 0 0 4px; }
        .cl-empty small { font-size: 13px; color: #9ca3af; }

        /* ── PAGINATION ── */
        .cl-pagination { margin-top: 28px; }

        /* ══════════════════════════════════════
           MODAL
        ══════════════════════════════════════ */
        .cl-modal {
            display: none;
            position: fixed; inset: 0;
            z-index: 10000;
            align-items: center; justify-content: center;
            padding: 16px;
        }
        .cl-modal.cl-modal-open { display: flex; }

        /* Backdrop */
        .cl-modal-backdrop {
            position: absolute; inset: 0;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }

        /* Box */
        .cl-modal-box {
            position: relative; z-index: 1;
            width: 100%; max-width: 640px;
            background: #fff; border-radius: 18px;
            box-shadow: 0 24px 64px rgba(15,23,42,.22);
            display: flex; flex-direction: column;
            max-height: 90vh;
            animation: modalIn .22s cubic-bezier(.34,1.56,.64,1);
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(.94) translateY(12px); }
            to   { opacity: 1; transform: scale(1)  translateY(0); }
        }

        /* Header */
        .cl-modal-hd {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 22px; border-bottom: 1px solid #f1f5f9; flex-shrink: 0;
        }
        .cl-modal-hd-left { display: flex; align-items: center; gap: 12px; }
        .cl-modal-icon {
            width: 40px; height: 40px; background: #eef2ff;
            border-radius: 10px; display: flex; align-items: center;
            justify-content: center; color: #4f46e5; flex-shrink: 0;
        }
        .cl-modal-title { font-size: 15px; font-weight: 700; color: var(--text); margin: 0 0 2px; }
        .cl-modal-sub   { font-size: 12px; color: var(--muted); margin: 0; }
        .cl-modal-close {
            width: 32px; height: 32px; border-radius: 8px;
            border: none; background: #f3f4f6; color: #374151;
            cursor: pointer; display: flex; align-items: center;
            justify-content: center; flex-shrink: 0; transition: background .15s;
        }
        .cl-modal-close:hover { background: #e5e7eb; }

        /* Body */
        .cl-modal-body { flex: 1; overflow-y: auto; padding: 20px 22px; }
        .cl-modal-body::-webkit-scrollbar { width: 5px; }
        .cl-modal-body::-webkit-scrollbar-track { background: transparent; }
        .cl-modal-body::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 999px; }

        /* Status row */
        .cl-modal-status-row {
            display: flex; align-items: center; gap: 10px; margin-bottom: 20px;
        }
        .cl-modal-updated { font-size: 12px; color: var(--muted); }

        /* Doc Section */
        .cl-doc-section { margin-bottom: 22px; }
        .cl-doc-section-label {
            display: flex; align-items: center; gap: 6px;
            font-size: 11px; font-weight: 700;
            letter-spacing: .07em; text-transform: uppercase;
            color: var(--muted); margin-bottom: 10px;
        }

        /* Doc Row */
        .cl-doc-row {
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            border: 1px solid var(--border); border-radius: 10px;
            margin-bottom: 8px; background: #fafbfd;
            transition: border-color .15s, background .15s;
        }
        .cl-doc-row:hover { border-color: #c7d2fe; background: #fafaff; }
        .cl-doc-info { display: flex; align-items: center; gap: 10px; min-width: 0; }
        .cl-doc-thumb {
            width: 36px; height: 36px; border-radius: 8px;
            background: #eef2ff; display: flex; align-items: center;
            justify-content: center; flex-shrink: 0;
        }
        .cl-doc-thumb-green { background: #ecfdf5; }
        .cl-doc-thumb-amber { background: #fffbeb; }
        .cl-doc-name {
            font-size: 13px; font-weight: 500; color: var(--text);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            max-width: 320px;
            font-family: 'DM Mono', monospace;
        }
        .cl-doc-btn {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 7px 14px; border-radius: 8px;
            font-size: 12px; font-weight: 600;
            background: #4f46e5; color: #fff;
            text-decoration: none; white-space: nowrap;
            flex-shrink: 0; margin-left: 10px;
            transition: background .15s, transform .1s;
        }
        .cl-doc-btn:hover { background: #4338ca; transform: translateY(-1px); }

        /* Empty inside modal */
        .cl-doc-empty {
            display: flex; flex-direction: column; align-items: center;
            gap: 8px; padding: 24px;
            border: 1px dashed #e5e7eb; border-radius: 10px; color: #9ca3af;
        }
        .cl-doc-empty p { font-size: 13px; margin: 0; }

        /* Footer */
        .cl-modal-ft {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 22px; border-top: 1px solid #f1f5f9; flex-shrink: 0;
        }
        .cl-modal-ft-note { font-size: 11.5px; color: #9ca3af; }

        /* Mobile */
        @media (max-width: 560px) {
            .cl-modal { align-items: flex-end; padding: 0; }
            .cl-modal-box { border-radius: 18px 18px 0 0; max-height: 92vh; }
            .cl-doc-name  { max-width: 170px; }
            .cl-modal-ft  { flex-direction: column; gap: 10px; text-align: center; }
            .cl-modal-ft .cl-btn { width: 100%; justify-content: center; }
        }
    </style>
</x-app-layout>
