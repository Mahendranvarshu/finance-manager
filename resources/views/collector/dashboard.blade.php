@extends('collector.layout')

@section('title', 'Dashboard')

@section('content')
<style>
    :root {
        --primary-teal: #0891b2;
        --primary-light: #06b6d4;
        --accent-green: #10b981;
        --accent-orange: #f59e0b;
        --bg-light: #f8fafc;
        --border-light: #e2e8f0;
    }

    body {
        background-color: var(--bg-light);
    }

    .dashboard-container {
        padding: 0.5rem; /* small padding */
        max-width: 100%;
    }

    /* Header Section */
    .welcome-header {
        background: linear-gradient(135deg, var(--primary-teal) 0%, var(--primary-light) 100%);
        color: white;
        padding: 0.75rem; /* small padding */
        border-radius: 1rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(8, 145, 178, 0.15);
    }

    .welcome-header h3 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .welcome-header p {
        font-size: 0.875rem;
        opacity: 0.95;
        margin: 0;
    }

    /* Highlight Card */
    .highlight-card {
        background: linear-gradient(135deg, var(--primary-teal) 0%, var(--primary-light) 100%);
        color: white;
        padding: 0.75rem; /* small padding */
        border-radius: 1rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(8, 145, 178, 0.15);
    }

    .highlight-content {
        display: flex;
        justify-content: space-around;
        align-items: center;
        gap: 0.5rem;
    }

    .highlight-item {
        flex: 1;
        text-align: center;
    }

    .highlight-item .label {
        font-size: 0.75rem;
        opacity: 0.9;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .highlight-item .amount {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
    }

    .highlight-divider {
        width: 1px;
        height: 60px;
        background-color: rgba(255, 255, 255, 0.3);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    @media (min-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .stat-card {
        background: white;
        padding: 0.5rem; /* small padding */
        border-radius: 0.875rem;
        border: 1px solid var(--border-light);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .stat-card .label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .stat-card .value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-teal);
        margin: 0;
    }

    /* Compact Table Styles */
    .collections-section,
    .parties-section {
        margin-bottom: 1.5rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding: 0 0.25rem; /* small padding */
    }

    .section-header h5 {
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
        color: #1e293b;
    }

    .section-header .btn-sm {
        padding: 0.2rem 0.4rem; /* small padding */
        font-size: 0.75rem;
    }

    .card {
        border: 1px solid var(--border-light);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border-radius: 0.875rem;
        overflow: hidden;
    }

    .card-body {
        padding: 0.2rem; /* small padding */
    }

    .table-responsive {
        overflow-x: auto;
    }

    table {
        font-size: 0.875rem;
    }

    thead th {
        background-color: #f1f5f9;
        padding: 0.4rem; /* small padding */
        font-weight: 600;
        color: #475569;
        border: none;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    tbody td {
        padding: 0.4rem; /* small padding */
        border-bottom: 1px solid var(--border-light);
        color: #334155;
    }

    tbody tr:last-child td {
        border-bottom: none;
    }

    tbody tr:hover {
        background-color: #f8fafc;
    }

    .amount-success {
        color: var(--accent-green);
        font-weight: 600;
    }

    .progress {
        height: 12px; /* smaller */
        border-radius: 0.5rem;
        background-color: #e2e8f0;
    }

    .progress-bar {
        background: linear-gradient(90deg, var(--primary-teal), var(--primary-light));
        font-size: 0.65rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        border-radius: 0.5rem;
    }

    .progress-bar.bg-success {
        background: linear-gradient(90deg, var(--accent-green), #34d399) !important;
    }

    .empty-state {
        text-align: center;
        padding: 1rem 0.5rem; /* smaller */
        color: #94a3b8;
    }

    .empty-state-icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        opacity: 0.5;
    }

    /* Mobile optimizations */
    @media (max-width: 576px) {
        .dashboard-container {
            padding: 0.25rem; /* even smaller */
        }

        .welcome-header,
        .highlight-card {
            padding: 0.5rem; /* smaller */
        }

        .highlight-content {
            flex-direction: column;
            gap: 0.25rem; /* smaller */
        }

        .highlight-divider {
            display: none;
        }

        .stats-grid {
            gap: 0.25rem; /* smaller */
        }

        .stat-card {
            padding: 0.3rem; /* smaller */
        }

        table {
            font-size: 0.8rem;
        }

        thead th,
        tbody td {
            padding: 0.3rem 0.25rem; /* smaller */
        }

        .progress {
            height: 10px; /* smaller */
        }
    }
</style>

<div class="dashboard-container">
    <!-- Welcome Header -->
    <div class="welcome-header">
        <h3>Welcome, {{ $collector->name }}!</h3>
        <p>Here's your collection summary for today</p>
    </div>

    <!-- Highlight Card: Today's & Week's Collection -->
    <div class="highlight-card">
        <div class="highlight-content">
            <div class="highlight-item">
                <div class="label">Today's Collection</div>
                <p class="amount">₹{{ number_format($todayTotal, 0) }}</p>
            </div>
            <div class="highlight-divider"></div>
            <div class="highlight-item">
                <div class="label">This Week</div>
                <p class="amount">₹{{ number_format($weekCollections, 0) }}</p>
            </div>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="label">This Month</div>
            <p class="value">₹{{ number_format($monthCollections, 0) }}</p>
        </div>
        <div class="stat-card">
            <div class="label">Collections</div>
            <p class="value">{{ $todayCollections->count() }}</p>
        </div>
        <div class="stat-card">
            <div class="label">Active Parties</div>
            <p class="value">{{ $activeParties->count() }}</p>
        </div>
    </div>

    <!-- Today's Collections Section -->
    <div class="collections-section">
        <div class="section-header">
            <h5><i class="bi bi-clock-history"></i> Today's Collections</h5>
            <a href="{{ route('collector.collection.create') }}" class="btn btn-sm btn-primary" style="background-color: var(--primary-teal); border: none;">
                <i class="bi bi-plus-circle"></i> Add
            </a>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Party</th>
                                <th class="text-end">Amount</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todayCollections as $collection)
                            <tr>
                                <td>{{ $collection->created_at->format('h:i A') }}</td>
                                <td><strong>{{ \Str::limit($collection->party->name, 14) }}</strong></td>
                                <td class="text-end amount-success">₹{{ number_format($collection->amount_collected, 0) }}</td>
                                <td>{{ \Str::limit($collection->remarks ?? '-', 12) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">📭</div>
                                        <p>No collections today</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Parties Section -->
    <div class="parties-section">
        <div class="section-header">
            <h5><i class="bi bi-people"></i> Active Parties</h5>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive" style="max-height: 400px;">
                    <table class="table table-sm mb-0">
                        <thead class="sticky-top">
                            <tr>
                                <th>Party</th>
                                <th class="text-end">Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeParties as $item)
                            <tr>
                                <td>
                                    <div class="fw-semibold" style="font-size: 0.875rem;">{{ \Str::limit($item['party']->name, 16) }}</div>
                                    <small class="text-muted">₹{{ number_format($item['collected'], 0) }}/₹{{ number_format($item['party']->loan_amount + $item['party']->interest_amount, 0) }}</small>
                                </td>
                                <td class="text-end">
                                    <div class="progress" style="height: 16px; width: 70px; margin-left: auto;">
                                        <div class="progress-bar {{ $item['progress'] >= 100 ? 'bg-success' : '' }}" 
                                             role="progressbar" 
                                             style="width: {{ min(100, $item['progress']) }}%">
                                            {{ number_format($item['progress'], 0) }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">👥</div>
                                        <p>No active parties</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
