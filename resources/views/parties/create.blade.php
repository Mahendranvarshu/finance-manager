@extends('layouts.app')

@section('title', 'Add New Party')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Add New Party</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('parties.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="dl_no" class="form-label">DL Number</label>
                            <input type="text" class="form-control @error('dl_no') is-invalid @enderror" 
                                   id="dl_no" name="dl_no" value="{{ old('dl_no') }}">
                            @error('dl_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="store_name" class="form-label">Store Name</label>
                            <input type="text" class="form-control @error('store_name') is-invalid @enderror" 
                                   id="store_name" name="store_name" value="{{ old('store_name') }}">
                            @error('store_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="2">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="loan_amount" class="form-label">Loan Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('loan_amount') is-invalid @enderror" 
                                   id="loan_amount" name="loan_amount" value="{{ old('loan_amount') }}" required>
                            @error('loan_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="interest_amount" class="form-label">Interest Amount</label>
                            <input type="number" step="0.01" class="form-control @error('interest_amount') is-invalid @enderror" 
                                   id="interest_amount" name="interest_amount" value="{{ old('interest_amount', 0) }}">
                            @error('interest_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="total_days" class="form-label">Total Days <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('total_days') is-invalid @enderror" 
                                   id="total_days" name="total_days" value="{{ old('total_days') }}" required>
                            @error('total_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="daily_amount" class="form-label">Daily Amount (Auto-calculated)</label>
                            <input type="number" step="0.01" class="form-control @error('daily_amount') is-invalid @enderror" 
                                   id="daily_amount" name="daily_amount" value="{{ old('daily_amount') }}" readonly>
                            @error('daily_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Calculated automatically</small>
                        </div>
                        <div class="col-md-4">
                            <label for="starting_date" class="form-label">Starting Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('starting_date') is-invalid @enderror" 
                                   id="starting_date" name="starting_date" value="{{ old('starting_date') }}" required>
                            @error('starting_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="ending_date" class="form-label">Ending Date (Auto-calculated)</label>
                            <input type="date" class="form-control @error('ending_date') is-invalid @enderror" 
                                   id="ending_date" name="ending_date" value="{{ old('ending_date') }}" readonly>
                            @error('ending_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Calculated automatically</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="collector_id" class="form-label">Collector</label>
                            <select class="form-select @error('collector_id') is-invalid @enderror" 
                                    id="collector_id" name="collector_id">
                                <option value="">Select Collector</option>
                                @foreach($collectors as $collector)
                                    <option value="{{ $collector->id }}" {{ old('collector_id') == $collector->id ? 'selected' : '' }}>
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
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="default" {{ old('status') == 'default' ? 'selected' : '' }}>Default</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('parties.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Party</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loanAmount = document.getElementById('loan_amount');
    const interestAmount = document.getElementById('interest_amount');
    const totalDays = document.getElementById('total_days');
    const dailyAmount = document.getElementById('daily_amount');
    const startingDate = document.getElementById('starting_date');
    const endingDate = document.getElementById('ending_date');

    function calculateDailyAmount() {
        const loan = parseFloat(loanAmount.value) || 0;
        const interest = parseFloat(interestAmount.value) || 0;
        const days = parseInt(totalDays.value) || 0;

        if (days > 0) {
            const total = loan + interest;
            const daily = total / days;
            dailyAmount.value = daily.toFixed(2);
        } else {
            dailyAmount.value = '';
        }
    }

    function calculateEndingDate() {
        const start = startingDate.value;
        const days = parseInt(totalDays.value) || 0;

        if (start && days > 0) {
            const startDate = new Date(start);
            const endDate = new Date(startDate);
            endDate.setDate(endDate.getDate() + days - 1);
            
            const year = endDate.getFullYear();
            const month = String(endDate.getMonth() + 1).padStart(2, '0');
            const day = String(endDate.getDate()).padStart(2, '0');
            endingDate.value = `${year}-${month}-${day}`;
        } else {
            endingDate.value = '';
        }
    }

    loanAmount.addEventListener('input', calculateDailyAmount);
    interestAmount.addEventListener('input', calculateDailyAmount);
    totalDays.addEventListener('input', function() {
        calculateDailyAmount();
        calculateEndingDate();
    });
    startingDate.addEventListener('change', calculateEndingDate);
});
</script>
@endpush
@endsection

