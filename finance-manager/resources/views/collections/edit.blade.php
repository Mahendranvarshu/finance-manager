@extends('layouts.app')

@section('title', 'Edit Collection')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0"><i class="bi bi-pencil"></i> Edit Collection</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('collections.update', $collection) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="party_id" class="form-label">Party <span class="text-danger">*</span></label>
                            <select class="form-select @error('party_id') is-invalid @enderror" 
                                    id="party_id" name="party_id" required>
                                <option value="">Select Party</option>
                                @foreach($parties as $party)
                                    <option value="{{ $party->id }}" {{ old('party_id', $collection->party_id) == $party->id ? 'selected' : '' }}>
                                        {{ $party->name }} - ₹{{ number_format($party->loan_amount, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('party_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="collector_id" class="form-label">Collector <span class="text-danger">*</span></label>
                            <select class="form-select @error('collector_id') is-invalid @enderror" 
                                    id="collector_id" name="collector_id" required>
                                <option value="">Select Collector</option>
                                @foreach($collectors as $collector)
                                    <option value="{{ $collector->id }}" {{ old('collector_id', $collection->collector_id) == $collector->id ? 'selected' : '' }}>
                                        {{ $collector->name }} ({{ $collector->area ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('collector_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                   id="date" name="date" value="{{ old('date', $collection->date->format('Y-m-d')) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="amount_collected" class="form-label">Amount Collected <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('amount_collected') is-invalid @enderror" 
                                   id="amount_collected" name="amount_collected" value="{{ old('amount_collected', $collection->amount_collected) }}" required>
                            @error('amount_collected')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="day_number" class="form-label">Day Number</label>
                            <input type="number" class="form-control @error('day_number') is-invalid @enderror" 
                                   id="day_number" name="day_number" value="{{ old('day_number', $collection->day_number) }}">
                            @error('day_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="remaining_amount" class="form-label">Remaining Amount</label>
                            <input type="number" step="0.01" class="form-control @error('remaining_amount') is-invalid @enderror" 
                                   id="remaining_amount" name="remaining_amount" value="{{ old('remaining_amount', $collection->remaining_amount) }}">
                            @error('remaining_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="remarks" class="form-label">Remarks</label>
                            <input type="text" class="form-control @error('remarks') is-invalid @enderror" 
                                   id="remarks" name="remarks" value="{{ old('remarks', $collection->remarks) }}">
                            @error('remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('collections.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Collection</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

