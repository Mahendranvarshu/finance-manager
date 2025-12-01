@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<style>
    :root {
        --primary: #0891b2;
        --primary-light: #06b6d4;
        --secondary: #0ea5e9;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --purple: #a78bfa;
        --bg-light: #f8fafc;
        --bg-card: #ffffff;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --border-color: #e2e8f0;
        --shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: var(--bg-light);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        color: var(--text-primary);
    }

    .dashboard-wrapper {
        max-width: 100%;
        padding: 1rem 0.75rem;
        margin: 0 auto;
        padding-bottom: 6rem;
    }

    /* Header with icon */
    .dashboard-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding: 0 0.5rem;
    }

    .app-icon {
        font-size: 1.5rem;
    }

    .notification-icon {
        font-size: 1.25rem;
        cursor: pointer;
    }

    /* Page title */
    .page-title {
        font-size: 1.875rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
        letter-spacing: -0.5px;
    }

    /* Main highlight card */
    .highlight-card {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        border-radius: 20px;
        padding: 1.5rem 1.25rem;
        color: white;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-lg);
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .highlight-item {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .highlight-item:nth-child(2) {
        align-items: flex-end;
        padding-left: 1rem;
        border-left: 1px solid rgba(255, 255, 255, 0.3);
    }

    .highlight-icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        opacity: 0.9;
    }

    .highlight-label {
        font-size: 0.8rem;
        font-weight: 600;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 0.3rem;
    }

    .highlight-value {
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    /* Section titles */
    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        color: var(--text-primary);
        padding: 0 0.5rem;
    }

    /* Quick actions grid */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .action-card {
        background: var(--bg-card);
        border: 1.5px solid var(--border-color);
        border-radius: 16px;
        padding: 1.25rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: inherit;
    }

    .action-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary);
    }

    .action-icon-wrapper {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 0.75rem;
        background: rgba(8, 145, 178, 0.1);
    }

    .action-card:nth-child(1) .action-icon-wrapper { background: rgba(16, 185, 129, 0.1); }
    .action-card:nth-child(2) .action-icon-wrapper { background: rgba(8, 145, 178, 0.1); }
    .action-card:nth-child(3) .action-icon-wrapper { background: rgba(245, 158, 11, 0.1); }
    .action-card:nth-child(4) .action-icon-wrapper { background: rgba(14, 165, 233, 0.1); }
    .action-card:nth-child(5) .action-icon-wrapper { background: rgba(8, 145, 178, 0.1); }
    .action-card:nth-child(6) .action-icon-wrapper { background: rgba(167, 139, 250, 0.1); }

    .action-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Section card */
    .data-card {
        background: var(--bg-card);
        border-radius: 16px;
        box-shadow: var(--shadow);
        margin-bottom: 1.25rem;
        overflow: hidden;
        border: 1px solid var(--border-color);
    }

    .data-card-header {
        background: linear-gradient(135deg, rgba(8, 145, 178, 0.05), rgba(14, 165, 233, 0.05));
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }

    .data-card-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .data-card-icon {
        font-size: 1.25rem;
    }

    .view-all-btn {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.4rem 0.875rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        white-space: nowrap;
    }

    .view-all-btn:hover {
        background: var(--primary-light);
        transform: translateY(-2px);
    }

    .data-card-content {
        padding: 0.75rem;
    }

    /* Compact table */
    .compact-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }

    .compact-table thead {
        background: var(--bg-light);
    }

    .compact-table th {
        padding: 0.6rem;
        text-align: left;
        font-weight: 700;
        font-size: 0.7rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border-bottom: 1px solid var(--border-color);
    }

    .compact-table td {
        padding: 0.75rem 0.6rem;
        border-bottom: 1px solid var(--border-color);
    }

    .compact-table tbody tr:hover {
        background: rgba(8, 145, 178, 0.03);
    }

    .compact-table tbody tr:last-child td {
        border-bottom: none;
    }

    .row-label {
        font-weight: 700;
        color: var(--text-primary);
        font-size: 0.9rem;
    }

    .row-sublabel {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-top: 0.2rem;
    }

    .amount-value {
        font-weight: 700;
        color: var(--primary);
    }

    .empty-state {
        text-align: center;
        padding: 2rem 1rem;
        color: var(--text-secondary);
        font-size: 0.85rem;
    }

    /* Progress bar */
    .progress-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .progress-bar {
        flex: 1;
        height: 6px;
        background: var(--border-color);
        border-radius: 3px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary), var(--primary-light));
        border-radius: 3px;
    }

    .progress-percent {
        font-weight: 700;
        font-size: 0.75rem;
        color: var(--text-primary);
        min-width: 30px;
        text-align: right;
    }

    .like-btn {
        background: none;
        border: none;
        font-size: 1.25rem;
        cursor: pointer;
        padding: 0.25rem;
        transition: transform 0.2s ease;
    }

    .like-btn:hover {
        transform: scale(1.2);
    }

    /* Responsive */
    @media (max-width: 640px) {
        .dashboard-wrapper {
            padding: 0.75rem 0.5rem;
            padding-bottom: 5rem;
        }

        .quick-actions {
            gap: 0.6rem;
        }

        .action-card {
            padding: 1rem;
        }

        .action-icon-wrapper {
            width: 45px;
            height: 45px;
            font-size: 1.25rem;
        }

        .action-label {
            font-size: 0.8rem;
        }

        .data-card-header {
            padding: 0.75rem;
        }

        .compact-table th {
            padding: 0.5rem;
            font-size: 0.65rem;
        }

        .compact-table td {
            padding: 0.6rem 0.5rem;
            font-size: 0.8rem;
        }

        .highlight-card {
            padding: 1.25rem 1rem;
        }

        .page-title {
            font-size: 1.625rem;
        }
    }

    @media (min-width: 768px) {
        .dashboard-wrapper {
            max-width: 1024px;
            padding: 1.5rem 2rem;
        }

        .quick-actions {
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .action-card {
            padding: 1.5rem 1.25rem;
        }

        .highlight-card {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>

<div class="dashboard-wrapper">
    <!-- Top Bar -->
    <div class="dashboard-top">
        <div class="app-icon">👤</div>
        <div class="notification-icon">📌</div>
    </div>

    <!-- Page Title -->
    <div class="page-title">Finance Collection</div>

    <!-- Highlight Card -->
    <div class="highlight-card">
        <div class="highlight-item">
            <div class="highlight-icon">📊</div>
            <div class="highlight-label">Today's Collection</div>
            <div class="highlight-value">₹{{ number_format($todayCollections, 0) }}</div>
        </div>
        <div class="highlight-item">
            <div class="highlight-icon">📈</div>
            <div class="highlight-label">Total Balance</div>
            <div class="highlight-value">₹{{ number_format($totalCollected, 0) }}</div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="section-title">Quick Actions</div>
    <div class="quick-actions">
        <a href="{{ route('collections.create') }}" class="action-card">
            <div class="action-icon-wrapper">➕</div>
            <div class="action-label">Add Collection</div>
        </a>
        <a href="#" class="action-card">
            <div class="action-icon-wrapper">📱</div>
            <div class="action-label">Scan QR</div>
        </a>
        <a href="{{ route('collections.index') }}" class="action-card">
            <div class="action-icon-wrapper">📋</div>
            <div class="action-label">Collections</div>
        </a>
        <a href="{{ route('parties.index') }}" class="action-card">
            <div class="action-icon-wrapper">👥</div>
            <div class="action-label">Parties</div>
        </a>
        <a href="#" class="action-card">
            <div class="action-icon-wrapper">🏪</div>
            <div class="action-label">Shops</div>
        </a>
        <a href="#" class="action-card">
            <div class="action-icon-wrapper">📖</div>
            <div class="action-label">Day Book</div>
        </a>
    </div>

    <!-- Recent Collections Section -->
    <div class="section-title">Recent Collections</div>
    <div class="data-card">
        <div class="data-card-header">
            <div class="data-card-title">
                <span class="data-card-icon">⏱️</span>
                Latest Activity
            </div>
            <a href="{{ route('collections.index') }}" class="view-all-btn">View All</a>
        </div>
        <div class="data-card-content">
            @forelse($recentCollections as $collection)
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Party</th>
                            <th>Collector</th>
                            <th style="text-align: right;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $collection->date->format('M d') }}</td>
                            <td class="row-label">{{ \Str::limit($collection->party->name, 16) }}</td>
                            <td class="row-sublabel">{{ \Str::limit($collection->collector->name, 12) }}</td>
                            <td style="text-align: right;" class="amount-value">₹ {{ number_format($collection->amount_collected, 0) }}</td>
                        </tr>
                    </tbody>
                </table>
            @empty
                <div class="empty-state">No collections recorded yet</div>
            @endforelse
        </div>
    </div>

    <!-- Active Parties Section -->
    <div class="section-title">Active Parties</div>
    <div class="data-card">
        <div class="data-card-header">
            <div class="data-card-title">
                <span class="data-card-icon">👥</span>
                Loan Progress
            </div>
            <a href="{{ route('parties.index') }}" class="view-all-btn">View All</a>
        </div>
        <div class="data-card-content">
            @forelse($partiesWithProgress as $item)
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th>Party</th>
                            <th>Progress</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="row-label">{{ \Str::limit($item['party']->name, 18) }}</div>
                                <div class="row-sublabel">{{ number_format($item['collected'], 0) }}/{{ number_format($item['party']->loan_amount + $item['party']->interest_amount, 0) }}</div>
                            </td>
                            <td>
                                <div class="progress-row">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ min(100, $item['progress']) }}%;"></div>
                                    </div>
                                    <span class="progress-percent">{{ number_format($item['progress'], 0) }}%</span>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <button class="like-btn" onclick="toggleLike(this)" title="Add to favorites">🤍</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            @empty
                <div class="empty-state">No active parties at the moment</div>
            @endforelse
        </div>
    </div>
</div>

<script>
    function toggleLike(btn) {
        btn.classList.toggle('active');
        btn.textContent = btn.classList.contains('active') ? '❤️' : '🤍';
    }
</script>

@endsection
