@extends('layouts.app')

@section('title', 'Collectors')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-person-badge"></i> Collectors</h2>
    <a href="{{ route('collectors.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add New Collector
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <form method="GET" action="{{ route('collectors.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name, area, username..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('collectors.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Phone</th>
                        <th>Area</th>
                        <th class="text-center">Parties</th>
                        <th class="text-center">Collections</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collectors as $collector)
                    <tr>
                        <td><strong>{{ $collector->name }}</strong></td>
                        <td>{{ $collector->username }}</td>
                        <td>{{ $collector->phone ?? '-' }}</td>
                        <td>{{ $collector->area ?? '-' }}</td>
                        <td class="text-center">{{ $collector->parties_count }}</td>
                        <td class="text-center">{{ $collector->collections_count }}</td>
                        <td>
                            <span class="badge bg-{{ $collector->status == 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($collector->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('collectors.show', $collector) }}" class="btn btn-outline-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('collectors.edit', $collector) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('collectors.destroy', $collector) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this collector?');">
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
                        <td colspan="8" class="text-center text-muted py-4">No collectors found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($collectors->hasPages())
    <div class="card-footer bg-white">
        {{ $collectors->links() }}
    </div>
    @endif
</div>
@endsection

