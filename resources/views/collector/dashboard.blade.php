@extends('collector.layout')

@section('title', 'Dashboard')

@section('content')
<div class="row g-3">
    <div class="col-12">
        <div class="p-4 bg-primary text-white rounded">
            <h3 class="mb-1">Welcome, {{ $collector->name }}!</h3>
            <p class="mb-0">Here's your collection summary for today</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="col-12 col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <small class="text-muted">Today's Collection</small>
                <div class="h4 mt-2 text-success">₹ {{ number_format($todayTotal, 2) }}</div>
                <small class="text-muted">{{ $todayCollections->count() }} collections</small>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <small class="text-muted">This Week</small>
                <div class="h4 mt-2 text-primary">₹ {{ number_format($weekCollections, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <small class="text-muted">This Month</small>
                <div class="h4 mt-2 text-info">₹ {{ number_format($monthCollections, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- Today's Collections -->
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Today's Collections</h5>
                <a href="{{ route('collector.collection.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Collection
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
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
                                <td><strong>{{ $collection->party->name }}</strong></td>
                                <td class="text-end fw-semibold text-success">₹ {{ number_format($collection->amount_collected, 2) }}</td>
                                <td>{{ $collection->remarks ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No collections today</td>
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
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-people"></i> Active Parties</h5>
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
                            @forelse($activeParties as $item)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $item['party']->name }}</div>
                                    <small class="text-muted">₹{{ number_format($item['collected'], 2) }}/₹{{ number_format($item['party']->loan_amount + $item['party']->interest_amount, 2) }}</small>
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

