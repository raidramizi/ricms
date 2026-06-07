<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment & Claim Submission Report</title>

    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }

        /* HEADER */
        .header {
            text-align: center;
            padding: 25px 20px 10px;
            border-bottom: 2px solid #e5e7eb;
        }

        .logo {
            width: 110px;
            margin-bottom: 10px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
        }

        .subtitle {
            font-size: 11px;
            color: #6b7280;
            margin-top: 4px;
        }

        /* KPI */
        .kpi {
            display: table;
            width: 100%;
            margin: 20px 0;
            border-spacing: 10px;
        }

        .box {
            display: table-cell;
            width: 25%;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
        }

        .box-title {
            font-size: 11px;
            color: #6b7280;
        }

        .box-value {
            font-size: 18px;
            font-weight: bold;
            margin-top: 5px;
        }

        .pending { border-top: 3px solid #f59e0b; }
        .verified { border-top: 3px solid #22c55e; }
        .headreview { border-top: 3px solid #8b5cf6; }
        .rejected { border-top: 3px solid #ef4444; }
        .recommended { border-top: 3px solid #10b981; }
        .total { border-top: 3px solid #3b82f6; }

        /* SUMMARY */
        .summary {
            background: #f3f4f6;
            padding: 12px;
            border-left: 4px solid #3b82f6;
            margin: 20px 0;
            border-radius: 6px;
        }

        /* CHART */
        .chart {
            text-align: center;
            margin: 20px 0;
        }

        .chart img {
            width: 100%;
            max-width: 650px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        /* TABLE */
        h4 {
            margin-top: 25px;
            font-size: 13px;
            border-left: 4px solid #3b82f6;
            padding-left: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        th {
            background: #111827;
            color: #fff;
            padding: 8px;
        }

        td {
            border-bottom: 1px solid #e5e7eb;
            padding: 7px;
        }

        tr:nth-child(even) {
            background: #f9fafb;
        }

        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 10px;
            color: white;
        }

        .pending-b { background: #f59e0b; }
        .rejected-b { background: #ef4444; }
        .recommended-b { background: #22c55e; }
        .verified-b { background: #3b82f6; }
        .head-b { background: #8b5cf6; }
    </style>
</head>

<body>

<!-- HEADER -->
<div class="header">

    @if(file_exists(public_path('storage/unikl.png')))
        <img src="file://{{ public_path('storage/unikl.png') }}" class="logo">
    @endif

    <div class="title">Submission Analytics Report</div>
    <div class="subtitle">Generated on {{ now()->format('d M Y, H:i') }}</div>
</div>

<!-- KPI -->
<div class="kpi">

    <div class="box pending">
        <div class="box-title">Pending</div>
        <div class="box-value">{{ $pendingCount }}</div>
    </div>

    <div class="box verified">
        <div class="box-title">Verified</div>
        <div class="box-value">{{ $verifiedCount ?? 0 }}</div>
    </div>

    <div class="box headreview">
        <div class="box-title">Under Head Review</div>
        <div class="box-value">{{ $underHeadReviewCount ?? 0 }}</div>
    </div>

    <div class="box rejected">
        <div class="box-title">Rejected</div>
        <div class="box-value">{{ $rejectedCount }}</div>
    </div>

    <div class="box recommended">
        <div class="box-title">Recommended</div>
        <div class="box-value">{{ $recommendedCount }}</div>
    </div>

    <div class="box total">
        <div class="box-title">Total</div>
        <div class="box-value">{{ $totalCount }}</div>
    </div>

</div>

<!-- CHART (FIXED - BASE64 SAFE) -->
<div class="chart">
    <h4>Submission Trend</h4>

    @php
        $chartPath = storage_path('app/public/chart.png');
        $chartBase64 = null;

        if (file_exists($chartPath)) {
            $chartBase64 = base64_encode(file_get_contents($chartPath));
        }
    @endphp

    @if($chartBase64)
        <img src="data:image/png;base64,{{ $chartBase64 }}">
    @else
        <p style="color:#9ca3af;">No chart available</p>
    @endif
</div>

<!-- SUMMARY -->
<div class="summary">
    <b>Analysis Summary</b>
    <p>{{ $analysis }}</p>
</div>

<!-- TABLE -->
<h4>Submission Details</h4>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Staff</th>
            <th>Type</th>
            <th>Category</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @foreach($submissions as $s)
        <tr>
             <td>{{ $loop->iteration }}</td>
            <td>{{ $s->user->name ?? '-' }}</td>
            <td>{{ $s->type->name ?? '-' }}</td>
            <td>{{ $s->category->name ?? '-' }}</td>
            <td>
                @if($s->status == 'pending_admin')
                    <span class="badge pending-b">Pending</span>
                @elseif($s->status == 'verified_admin')
                    <span class="badge verified-b">Verified</span>
                @elseif($s->status == 'sent_to_head')
                    <span class="badge head-b">Head Review</span>
                @elseif($s->status == 'approved_head')
                    <span class="badge recommended-b">Recommended</span>
                @else
                    <span class="badge rejected-b">Rejected</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
