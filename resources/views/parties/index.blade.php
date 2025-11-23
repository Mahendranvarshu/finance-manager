@extends('layouts.app')

@section('title', 'Parties')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people"></i> Parties</h2>
    <a href="{{ route('parties.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add New Party
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <form method="GET" action="{{ route('parties.index') }}" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search by name, DL no..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="default" {{ request('status') == 'default' ? 'selected' : '' }}>Default</option>
                </select>
            </div>
            <div class="col-md-3">
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
                <a href="{{ route('parties.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>DL No</th>
                        <th>Name</th>
                        <th>Store Name</th>
                        <th>Phone</th>
                        <th>Collector</th>
                        <th class="text-end">Loan Amount</th>
                        <th class="text-end">Collected</th>
                        <th class="text-end">Remaining</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parties as $party)
                    <tr>
                        <td>{{ $party->dl_no ?? '-' }}</td>
                        <td><strong>{{ $party->name }}</strong></td>
                        <td>{{ $party->store_name ?? '-' }}</td>
                        <td>{{ $party->phone ?? '-' }}</td>
                        <td>{{ $party->collector->name ?? '-' }}</td>
                        <td class="text-end">₹ {{ number_format($party->loan_amount, 2) }}</td>
                        <td class="text-end text-success">₹ {{ number_format($party->collected_amount ?? 0, 2) }}</td>
                        <td class="text-end text-warning">₹ {{ number_format($party->remaining_amount ?? 0, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $party->status == 'active' ? 'success' : ($party->status == 'completed' ? 'info' : 'danger') }}">
                                {{ ucfirst($party->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('parties.show', $party) }}" class="btn btn-outline-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('parties.edit', $party) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('parties.destroy', $party) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this party?');">
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
                        <td colspan="10" class="text-center text-muted py-4">No parties found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($parties->hasPages())
    <div class="card-footer bg-white">
        {{ $parties->links() }}
    </div>
    @endif
</div>
@endsection

