@extends('layouts.app')

@section('title', 'Monthly Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-calendar-month"></i> Monthly Report</h2>
    <form method="GET" action="{{ route('report.monthly') }}" class="d-flex gap-2">
        <input type="month" name="date" class="form-control" value="{{ $start->format('Y-m') }}">
        <button type="submit" class="btn btn-primary">View</button>
    </form>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <strong>Month:</strong>
                <p class="h5">{{ $start->format('F Y') }}</p>
            </div>
            <div class="col-md-3">
                <strong>Total Collections:</strong>
                <p class="h5">{{ $collectionCount }}</p>
            </div>
            <div class="col-md-3">
                <strong>Total Collected:</strong>
                <p class="h5 text-success">₹ {{ number_format($totalCollected, 2) }}</p>
            </div>
            <div class="col-md-3">
                <strong>Average per Day:</strong>
                <p class="h5">₹ {{ number_format($byDate->count() > 0 ? $totalCollected / $byDate->count() : 0, 2) }}</p>
            </div>
        </div>
    </div>
</div>

@if($byCollector->count() > 0)
<div class="card shadow-sm mb-3">
    <div class="card-header bg-white">
        <h5 class="mb-0">Collections by Collector</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Collector</th>
                        <th class="text-end">Count</th>
                        <th class="text-end">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($byCollector as $item)
                    <tr>
                        <td>{{ $item['collector']->name }}</td>
                        <td class="text-end">{{ $item['count'] }}</td>
                        <td class="text-end">₹ {{ number_format($item['total'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@if($topParties->count() > 0)
<div class="card shadow-sm mb-3">
    <div class="card-header bg-white">
        <h5 class="mb-0">Top Parties</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Party</th>
                        <th class="text-end">Collections</th>
                        <th class="text-end">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topParties as $item)
                    <tr>
                        <td>{{ $item['party']->name }}</td>
                        <td class="text-end">{{ $item['count'] }}</td>
                        <td class="text-end">₹ {{ number_format($item['total'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Daily Summary</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th class="text-end">Count</th>
                        <th class="text-end">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($byDate as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item['date'])->format('M d, Y') }}</td>
                        <td class="text-end">{{ $item['count'] }}</td>
                        <td class="text-end">₹ {{ number_format($item['total'], 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">No collections found for this month</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

