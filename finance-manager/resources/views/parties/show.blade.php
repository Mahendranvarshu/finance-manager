@extends('layouts.app')

@section('title', 'Party Details')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-person"></i> Party Details</h2>
            <div class="btn-group">
                <a href="{{ route('parties.edit', $party) }}" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="{{ route('collections.create', ['party_id' => $party->id]) }}" class="btn btn-success">
                    <i class="bi bi-cash-coin"></i> Add Collection
                </a>
                <a href="{{ route('parties.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Party Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>DL Number:</strong>
                                <p>{{ $party->dl_no ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Name:</strong>
                                <p>{{ $party->name }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Store Name:</strong>
                                <p>{{ $party->store_name ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Phone:</strong>
                                <p>{{ $party->phone ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <strong>Address:</strong>
                            <p>{{ $party->address ?? '-' }}</p>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Collector:</strong>
                                <p>{{ $party->collector->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Status:</strong>
                                <p>
                                    <span class="badge bg-{{ $party->status == 'active' ? 'success' : ($party->status == 'completed' ? 'info' : 'danger') }}">
                                        {{ ucfirst($party->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mt-3">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Loan Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Loan Amount:</strong>
                                <p class="h5">₹ {{ number_format($party->loan_amount, 2) }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>Interest Amount:</strong>
                                <p class="h5">₹ {{ number_format($party->interest_amount ?? 0, 2) }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>Total Amount:</strong>
                                <p class="h5">₹ {{ number_format($party->loan_amount + ($party->interest_amount ?? 0), 2) }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Daily Amount:</strong>
                                <p class="h5">₹ {{ number_format($party->daily_amount ?? 0, 2) }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>Total Days:</strong>
                                <p class="h5">{{ $party->total_days }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>Progress:</strong>
                                <div class="progress mt-2" style="height: 25px;">
                                    <div class="progress-bar {{ $progress >= 100 ? 'bg-success' : 'bg-primary' }}" 
                                         role="progressbar" 
                                         style="width: {{ min(100, $progress) }}%">
                                        {{ number_format($progress, 1) }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Starting Date:</strong>
                                <p>{{ $party->starting_date->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>Ending Date:</strong>
                                <p>{{ $party->ending_date ? $party->ending_date->format('M d, Y') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mt-3">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Collection Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Total Collected:</strong>
                                <p class="h5 text-success">₹ {{ number_format($collected, 2) }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>Remaining:</strong>
                                <p class="h5 text-warning">₹ {{ number_format($remaining, 2) }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>Total Collections:</strong>
                                <p class="h5">{{ $party->collections->count() }}</p>
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
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($party->collections as $collection)
                                    <tr>
                                        <td>{{ $collection->date->format('M d') }}</td>
                                        <td class="text-end">₹ {{ number_format($collection->amount_collected, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-3">No collections yet</td>
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

