<!DOCTYPE html>
<html>
<head>
    <title>Head Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        /* ===== HEADER ===== */
        .header {
            text-align: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            height: 50px;
            margin-bottom: 5px;
        }

        h2 {
            margin: 5px 0;
        }

/* ===== KPI BOXES (SMALLER) ===== */
.kpi-container {
    width: 100%;
    margin-bottom: 15px;
    text-align: center;
}

.kpi {
    width: 22%;
    display: inline-block;
    padding: 6px 8px;
    margin-right: 1%;
    border-radius: 5px;
    color: #fff;
    font-size: 10px;
}

.kpi h3 {
    margin: 2px 0;
    font-size: 13px;
    font-weight: bold;
}

.kpi small {
    font-size: 9px;
}

/* colors remain same */
.kpi.total { background: #111827; }
.kpi.pending { background: #1d4ed8; }
.kpi.approved { background: #16a34a; }
.kpi.rejected { background: #dc2626; }
        /* ===== CHARTS ===== */
        .charts {
            width: 100%;
            margin-bottom: 20px;
        }

        .chart-box {
            width: 48%;
            display: inline-block;
            vertical-align: top;
            text-align: center;
        }

        .chart-box img {
            max-width: 100%;
            height: auto;
        }

        .chart-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        /* ===== TABLE ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background: #f3f4f6;
            font-size: 11px;
            text-align: left;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        tr:nth-child(even) {
            background: #fafafa;
        }

        /* ===== BADGES ===== */
        .badge {
            padding: 4px 8px;
            font-size: 10px;
            border-radius: 4px;
            color: white;
            display: inline-block;
        }

        .green { background: #16a34a; }
        .red { background: #dc2626; }
        .blue { background: #1d4ed8; }
        .yellow { background: #f59e0b; }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: right;
            color: #666;
        }
/* ===== ANALYTICS DESCRIPTION ===== */
.analytics-box {
    border: 1px solid #e5e7eb;
    padding: 10px 12px;
    border-radius: 6px;
    margin-bottom: 15px;
    background: #fafafa;
}

.analytics-box h4 {
    margin: 0 0 5px 0;
    font-size: 13px;
}

.analytics-box p {
    margin: 3px 0;
    font-size: 11px;
    line-height: 1.4;
}
    </style>
</head>

<body>

<!-- ===== HEADER ===== -->
<div class="header">
    @if(file_exists(public_path('storage/unikl.png')))
        <img src="{{ public_path('storage/unikl.png') }}" class="logo">
    @endif

    <h2>Head Submission Report</h2>
    <p>Generated at: {{ $generatedAt }}</p>
</div>

<!-- ===== KPI SUMMARY ===== -->
<div class="kpi-container">
    <div class="kpi total">
        <small>Total</small>
        <h3>{{ $total }}</h3>
    </div>

    <div class="kpi pending">
        <small>Pending</small>
        <h3>{{ $pending }}</h3>
    </div>

    <div class="kpi approved">
        <small>Recommended</small>
        <h3>{{ $recommended }}</h3>
    </div>

    <div class="kpi rejected">
        <small>Rejected</small>
        <h3>{{ $rejected }}</h3>
    </div>
</div>

<!-- ===== CHARTS SIDE BY SIDE ===== -->
<div class="charts">

    @if($statusChart)
    <div class="chart-box">
        <div class="chart-title">Status Distribution</div>
        <img src="{{ $statusChart }}">
    </div>
    @endif

    @if($trendChart)
    <div class="chart-box">
        <div class="chart-title">Submission Trend</div>
        <img src="{{ $trendChart }}">
    </div>
    @endif

</div>
{{-- ===== ANALYTICS DESCRIPTION ===== --}}
<div class="analytics-box">
    <h4>Analytics Summary</h4>

    <p>
        This report provides an overview of submission activities reviewed by the Head of Department.
        A total of <strong>{{ $total }}</strong> submissions were recorded during this period.
    </p>

    <p>
        Out of the total,
        <strong>{{ $pending }}</strong> are currently pending review,
        <strong>{{ $recommended }}</strong> have been recommended for approval,
        and <strong>{{ $rejected }}</strong> were rejected.
    </p>

    <p>
        The charts below illustrate the distribution of submission statuses and the overall trend of submissions,
        helping to identify workload patterns and decision outcomes over time.
    </p>
    <p>
    @if($recommended > $rejected)
        The majority of submissions were recommended, indicating a generally positive evaluation trend.
    @elseif($rejected > $recommended)
        A higher number of submissions were rejected, suggesting stricter evaluation criteria during this period.
    @else
        The number of recommended and rejected submissions is balanced.
    @endif
</p>
</div>

<!-- ===== TABLE ===== -->
<table>
    <thead>
        <tr>
            <th> No </th>

            <th>Staff</th>
            <th>Category</th>
            <th>Type</th>
            <th style="width:15%">Status</th>
        </tr>
    </thead>

    <tbody>
        @foreach($submissions as $i => $s)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $s->user->name ?? '-' }}</td>
            <td>{{ $s->category->name ?? '-' }}</td>
            <td>{{ $s->type->name ?? '-' }}</td>
            <td>
                @if($s->status == 'sent_to_head')
                    <span class="badge blue">Pending</span>
                @elseif($s->status == 'approved_head')
                    <span class="badge green">Recommended</span>
                @elseif($s->status == 'rejected_head')
                    <span class="badge red">Rejected</span>
                @else
                    <span class="badge yellow">Unknown</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- ===== FOOTER ===== -->
<div class="footer">
    Generated automatically • UniKL System
</div>

</body>
</html>
