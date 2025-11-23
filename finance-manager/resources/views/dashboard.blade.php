@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row g-3">
    <div class="col-12">
        <div class="p-4 hero d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h4 mb-1">Dashboard</h1>
                <p class="mb-0 text-muted">Overview of your finance management system</p>
            </div>
            <div class="text-end">
                <div class="small text-muted">As of</div>
                <div class="fw-semibold">{{ \Carbon\Carbon::now()->format('F d, Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card card-stats shadow-sm">
            <div class="card-body">
                <small class="text-muted">Total Parties</small>
                <div class="h5 mt-1">{{ $totalParties }}</div>
                <div class="text-muted small">Active: {{ $activeParties }}</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card card-stats shadow-sm">
            <div class="card-body">
                <small class="text-muted">Active Collectors</small>
                <div class="h5 mt-1">{{ $totalCollectors }}</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card card-stats shadow-sm">
            <div class="card-body">
                <small class="text-muted">Today's Collection</small>
                <div class="h5 mt-1 text-success">₹ {{ number_format($todayCollections, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card card-stats shadow-sm">
            <div class="card-body">
                <small class="text-muted">Month's Collection</small>
                <div class="h5 mt-1 text-primary">₹ {{ number_format($monthCollections, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card card-stats shadow-sm">
            <div class="card-body">
                <small class="text-muted">Total Loan Amount</small>
                <div class="h5 mt-1">₹ {{ number_format($totalLoanAmount, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card card-stats shadow-sm">
            <div class="card-body">
                <small class="text-muted">Remaining Loan</small>
                <div class="h5 mt-1 text-warning">₹ {{ number_format($remainingLoanAmount, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card card-stats shadow-sm">
            <div class="card-body">
                <small class="text-muted">Week's Collection</small>
                <div class="h5 mt-1">₹ {{ number_format($weekCollections, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card card-stats shadow-sm">
            <div class="card-body">
                <small class="text-muted">Total Collected</small>
                <div class="h5 mt-1 text-success">₹ {{ number_format($totalCollected, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- Recent Collections -->
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Collections</h5>
                <a href="{{ route('collections.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Party</th>
                                <th>Collector</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentCollections as $collection)
                            <tr>
                                <td>{{ $collection->date->format('M d, Y') }}</td>
                                <td>{{ $collection->party->name }}</td>
                                <td>{{ $collection->collector->name }}</td>
                                <td class="text-end fw-semibold">₹ {{ number_format($collection->amount_collected, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No collections yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Parties -->
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-people"></i> Active Parties</h5>
                <a href="{{ route('parties.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 400px;">
                    <table class="table table-sm mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Party</th>
                                <th class="text-end">Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($partiesWithProgress as $item)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $item['party']->name }}</div>
                                    <small class="text-muted">{{ number_format($item['collected'], 2) }}/{{ number_format($item['party']->loan_amount + $item['party']->interest_amount, 2) }}</small>
                                </td>
                                <td class="text-end">
                                    <div class="progress" style="height: 20px; width: 80px; margin-left: auto;">
                                        <div class="progress-bar {{ $item['progress'] >= 100 ? 'bg-success' : 'bg-primary' }}" 
                                             role="progressbar" 
                                             style="width: {{ min(100, $item['progress']) }}%">
                                            {{ number_format($item['progress'], 0) }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">No active parties</td>
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

