@extends('layouts.app')

@section('title', 'Daily Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-calendar-day"></i> Daily Report</h2>
    <form method="GET" action="{{ route('report.daily') }}" class="d-flex gap-2">
        <input type="date" name="date" class="form-control" value="{{ $date->format('Y-m-d') }}">
        <button type="submit" class="btn btn-primary">View</button>
    </form>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <strong>Report Date:</strong>
                <p class="h5">{{ $date->format('F d, Y') }}</p>
            </div>
            <div class="col-md-4">
                <strong>Total Collections:</strong>
                <p class="h5">{{ $collectionCount }}</p>
            </div>
            <div class="col-md-4">
                <strong>Total Collected:</strong>
                <p class="h5 text-success">₹ {{ number_format($totalCollected, 2) }}</p>
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

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">All Collections</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Time</th>
                        <th>Party</th>
                        <th>Collector</th>
                        <th class="text-end">Amount</th>
                        <th class="text-end">Remaining</th>
                        <th>Day #</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collections as $collection)
                    <tr>
                        <td>{{ $collection->created_at->format('h:i A') }}</td>
                        <td>{{ $collection->party->name }}</td>
                        <td>{{ $collection->collector->name }}</td>
                        <td class="text-end text-success">₹ {{ number_format($collection->amount_collected, 2) }}</td>
                        <td class="text-end text-warning">₹ {{ number_format($collection->remaining_amount ?? 0, 2) }}</td>
                        <td>{{ $collection->day_number ?? '-' }}</td>
                        <td>{{ $collection->remarks ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No collections found for this date</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

