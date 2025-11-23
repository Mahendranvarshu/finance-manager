@extends('layouts.app')

@section('title', 'Edit Party')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0"><i class="bi bi-pencil"></i> Edit Party</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('parties.update', $party) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="dl_no" class="form-label">DL Number</label>
                            <input type="text" class="form-control @error('dl_no') is-invalid @enderror" 
                                   id="dl_no" name="dl_no" value="{{ old('dl_no', $party->dl_no) }}">
                            @error('dl_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $party->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="store_name" class="form-label">Store Name</label>
                            <input type="text" class="form-control @error('store_name') is-invalid @enderror" 
                                   id="store_name" name="store_name" value="{{ old('store_name', $party->store_name) }}">
                            @error('store_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $party->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="2">{{ old('address', $party->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="loan_amount" class="form-label">Loan Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('loan_amount') is-invalid @enderror" 
                                   id="loan_amount" name="loan_amount" value="{{ old('loan_amount', $party->loan_amount) }}" required>
                            @error('loan_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="interest_amount" class="form-label">Interest Amount</label>
                            <input type="number" step="0.01" class="form-control @error('interest_amount') is-invalid @enderror" 
                                   id="interest_amount" name="interest_amount" value="{{ old('interest_amount', $party->interest_amount) }}">
                            @error('interest_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="total_days" class="form-label">Total Days <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('total_days') is-invalid @enderror" 
                                   id="total_days" name="total_days" value="{{ old('total_days', $party->total_days) }}" required>
                            @error('total_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="daily_amount" class="form-label">Daily Amount</label>
                            <input type="number" step="0.01" class="form-control @error('daily_amount') is-invalid @enderror" 
                                   id="daily_amount" name="daily_amount" value="{{ old('daily_amount', $party->daily_amount) }}">
                            @error('daily_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="starting_date" class="form-label">Starting Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('starting_date') is-invalid @enderror" 
                                   id="starting_date" name="starting_date" value="{{ old('starting_date', $party->starting_date->format('Y-m-d')) }}" required>
                            @error('starting_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="ending_date" class="form-label">Ending Date</label>
                            <input type="date" class="form-control @error('ending_date') is-invalid @enderror" 
                                   id="ending_date" name="ending_date" value="{{ old('ending_date', $party->ending_date ? $party->ending_date->format('Y-m-d') : '') }}">
                            @error('ending_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="collector_id" class="form-label">Collector</label>
                            <select class="form-select @error('collector_id') is-invalid @enderror" 
                                    id="collector_id" name="collector_id">
                                <option value="">Select Collector</option>
                                @foreach($collectors as $collector)
                                    <option value="{{ $collector->id }}" {{ old('collector_id', $party->collector_id) == $collector->id ? 'selected' : '' }}>
                                        {{ $collector->name }} ({{ $collector->area ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('collector_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="active" {{ old('status', $party->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ old('status', $party->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="default" {{ old('status', $party->status) == 'default' ? 'selected' : '' }}>Default</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('parties.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Party</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

