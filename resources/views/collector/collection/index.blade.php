@extends('collector.layout')

@section('title', 'My Collections')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-list-ul"></i> My Collections</h2>
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-download"></i> Export
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('collector.collection.export-csv', request()->all()) }}">
                <i class="bi bi-filetype-csv"></i> Export to CSV
            </a></li>
            <li><a class="dropdown-item" href="{{ route('collector.collection.export-excel', request()->all()) }}">
                <i class="bi bi-file-earmark-excel"></i> Export to Excel
            </a></li>
        </ul>
        <a href="{{ route('collector.collection.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Collection
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <form method="GET" action="{{ route('collector.collection.index') }}" class="row g-3">
            <div class="col-md-3">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}" placeholder="Select Date">
            </div>
            <div class="col-md-3">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From Date">
            </div>
            <div class="col-md-3">
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To Date">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('collector.collection.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Party</th>
                        <th class="text-end">Amount</th>
                        <th class="text-end">Remaining</th>
                        <th>Day #</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collections as $collection)
                    <tr>
                        <td>{{ $collection->date->format('M d, Y') }}</td>
                        <td><strong>{{ $collection->party->name }}</strong></td>
                        <td class="text-end fw-semibold text-success">₹ {{ number_format($collection->amount_collected, 2) }}</td>
                        <td class="text-end text-warning">₹ {{ number_format($collection->remaining_amount ?? 0, 2) }}</td>
                        <td>{{ $collection->day_number ?? '-' }}</td>
                        <td>{{ Str::limit($collection->remarks ?? '-', 30) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No collections found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($collections->hasPages())
    <div class="card-footer bg-white">
        {{ $collections->links() }}
    </div>
    @endif
</div>
@endsection

