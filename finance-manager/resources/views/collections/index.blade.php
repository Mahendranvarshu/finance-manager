@extends('layouts.app')

@section('title', 'Collections')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-cash-coin"></i> Collections</h2>
    <a href="{{ route('collections.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add New Collection
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <form method="GET" action="{{ route('collections.index') }}" class="row g-3">
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From Date">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To Date">
            </div>
            <div class="col-md-2">
                <select name="party_id" class="form-select">
                    <option value="">All Parties</option>
                    @foreach($parties as $party)
                        <option value="{{ $party->id }}" {{ request('party_id') == $party->id ? 'selected' : '' }}>
                            {{ $party->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="collector_id" class="form-select">
                    <option value="">All Collectors</option>
                    @foreach($collectors as $collector)
                        <option value="{{ $collector->id }}" {{ request('collector_id') == $collector->id ? 'selected' : '' }}>
                            {{ $collector->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('collections.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>D L NO</th>
                        <th>Party</th>
                        <th>Collector</th>
                        <th class="text-end">Amount</th>
                        <th class="text-end">Remaining</th>
                        <th>Day #</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collections as $collection)
                    <tr>
                        <td>{{ $collection->date->format('M d, Y') }}</td>
                        <td>{{ $collection->party->dl_no }}</td>
                        <td><strong>{{ $collection->party->name }}</strong></td>
                        <td>{{ $collection->collector->name }}</td>
                        <td class="text-end fw-semibold text-success">₹ {{ number_format($collection->amount_collected, 2) }}</td>
                        <td class="text-end text-warning">₹ {{ number_format($collection->remaining_amount ?? 0, 2) }}</td>
                        <td>{{ $collection->day_number ?? '-' }}</td>
                        <td>{{ Str::limit($collection->remarks ?? '-', 30) }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('collections.show', $collection) }}" class="btn btn-outline-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('collections.edit', $collection) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('collections.destroy', $collection) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this collection?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No collections found</td>
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

