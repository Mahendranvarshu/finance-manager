@extends('collector.layout')

@section('title', 'My Reports')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-file-earmark-text"></i> My Reports</h2>
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-download"></i> Export
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('collector.collection.export-csv') }}">
                <i class="bi bi-filetype-csv"></i> Export to CSV
            </a></li>
            <li><a class="dropdown-item" href="{{ route('collector.collection.export-excel') }}">
                <i class="bi bi-file-earmark-excel"></i> Export to Excel
            </a></li>
        </ul>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <small class="text-muted">Today's Collection</small>
                <div class="h4 mt-2 text-success">₹ {{ number_format($todayTotal, 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <small class="text-muted">This Week</small>
                <div class="h4 mt-2 text-primary">₹ {{ number_format($weekTotal, 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <small class="text-muted">This Month</small>
                <div class="h4 mt-2 text-info">₹ {{ number_format($monthTotal, 2) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Daily Breakdown (This Month)</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th class="text-end">Collections</th>
                        <th class="text-end">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dailyBreakdown as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item['date'])->format('M d, Y') }}</td>
                        <td class="text-end">{{ $item['count'] }}</td>
                        <td class="text-end fw-semibold">₹ {{ number_format($item['total'], 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">No collections found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

