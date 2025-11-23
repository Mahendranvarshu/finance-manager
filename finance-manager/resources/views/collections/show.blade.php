@extends('layouts.app')

@section('title', 'Collection Details')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-cash-coin"></i> Collection Details</h2>
            <div class="btn-group">
                <a href="{{ route('collections.edit', $collection) }}" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="{{ route('collections.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Collection Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Date:</strong>
                        <p>{{ $collection->date->format('F d, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Day Number:</strong>
                        <p>{{ $collection->day_number ?? '-' }}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Party:</strong>
                        <p><a href="{{ route('parties.show', $collection->party) }}">{{ $collection->party->name }}</a></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Collector:</strong>
                        <p><a href="{{ route('collectors.show', $collection->collector) }}">{{ $collection->collector->name }}</a></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Amount Collected:</strong>
                        <p class="h5 text-success">₹ {{ number_format($collection->amount_collected, 2) }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Remaining Amount:</strong>
                        <p class="h5 text-warning">₹ {{ number_format($collection->remaining_amount ?? 0, 2) }}</p>
                    </div>
                </div>
                <div class="mb-3">
                    <strong>Remarks:</strong>
                    <p>{{ $collection->remarks ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

