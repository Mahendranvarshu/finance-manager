@extends('layouts.app')

@section('title', 'Collector Details')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-person-badge"></i> Collector Details</h2>
            <div class="btn-group">
                <a href="{{ route('collectors.edit', $collector) }}" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="{{ route('collectors.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Collector Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Name:</strong>
                                <p class="h5">{{ $collector->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Username:</strong>
                                <p class="h5">{{ $collector->username }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Phone:</strong>
                                <p>{{ $collector->phone ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Area:</strong>
                                <p>{{ $collector->area ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <strong>Status:</strong>
                            <p>
                                <span class="badge bg-{{ $collector->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($collector->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mt-3">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Total Parties:</strong>
                                <p class="h5">{{ $collector->parties->count() }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>Total Collections:</strong>
                                <p class="h5">{{ $collector->collections->count() }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>Total Collected:</strong>
                                <p class="h5 text-success">₹ {{ number_format($totalCollections, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Recent Collections</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 500px;">
                            <table class="table table-sm mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Date</th>
                                        <th>Party</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentCollections as $collection)
                                    <tr>
                                        <td>{{ $collection->date->format('M d') }}</td>
                                        <td>{{ $collection->party->name }}</td>
                                        <td class="text-end">₹ {{ number_format($collection->amount_collected, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">No collections yet</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

