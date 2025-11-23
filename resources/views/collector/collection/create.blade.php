@extends('collector.layout')

@section('title', 'New Collection')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0"><i class="bi bi-qr-code-scan"></i> New Collection Entry</h4>
            </div>
            <div class="card-body">
                <!-- QR Code Scanner / DL Number Input -->
                <div class="mb-4 p-4 bg-light rounded">
                    <label for="dl_no_input" class="form-label fw-bold">Scan QR Code or Enter DL Number</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text"><i class="bi bi-qr-code"></i></span>
                        <input type="text" 
                               class="form-control" 
                               id="dl_no_input" 
                               placeholder="Scan QR code or type DL number"
                               value="{{ old('dl_no', $dlNo ?? '') }}"
                               autofocus>
                        <button class="btn btn-primary" type="button" id="scan_qr_btn">
                            <i class="bi bi-camera"></i> Scan QR
                        </button>
                        <button class="btn btn-success" type="button" id="search_btn">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                    <small class="text-muted">Enter DL number and click Search to find party details</small>
                </div>

                <!-- Party Details (shown after search) -->
                @if($party)
                <div class="alert alert-info">
                    <h5><i class="bi bi-check-circle"></i> Party Found!</h5>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <strong>Name:</strong> {{ $party->name }}<br>
                            <strong>Store:</strong> {{ $party->store_name ?? '-' }}<br>
                            <strong>Phone:</strong> {{ $party->phone ?? '-' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Loan Amount:</strong> ₹{{ number_format($party->loan_amount, 2) }}<br>
                            <strong>Interest:</strong> ₹{{ number_format($party->interest_amount ?? 0, 2) }}<br>
                            <strong>Daily Amount:</strong> ₹{{ number_format($party->daily_amount_display ?? 0, 2) }}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <strong>Collected:</strong> ₹{{ number_format($party->collected_amount ?? 0, 2) }}
                        </div>
                        <div class="col-md-6">
                            <strong>Remaining:</strong> ₹{{ number_format($party->remaining_amount ?? 0, 2) }}
                        </div>
                    </div>
                </div>

                <!-- Collection Form -->
                <form action="{{ route('collector.collection.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="dl_no" value="{{ $party->dl_no }}">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="date" class="form-label">Collection Date <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('date') is-invalid @enderror" 
                                   id="date" 
                                   name="date" 
                                   value="{{ old('date', date('Y-m-d')) }}" 
                                   required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="amount_collected" class="form-label">Amount Collected <span class="text-danger">*</span></label>
                            <input type="number" 
                                   step="0.01" 
                                   class="form-control @error('amount_collected') is-invalid @enderror" 
                                   id="amount_collected" 
                                   name="amount_collected" 
                                   value="{{ old('amount_collected', $party->daily_amount_display ?? '') }}" 
                                   required>
                            @error('amount_collected')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Suggested: ₹{{ number_format($party->daily_amount_display ?? 0, 2) }}</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control @error('remarks') is-invalid @enderror" 
                                  id="remarks" 
                                  name="remarks" 
                                  rows="2">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('collector.dashboard') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle"></i> Complete Collection
                        </button>
                    </div>
                </form>
                @elseif($dlNo)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> No active party found with DL Number: <strong>{{ $dlNo }}</strong>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dlNoInput = document.getElementById('dl_no_input');
    const searchBtn = document.getElementById('search_btn');
    const scanQrBtn = document.getElementById('scan_qr_btn');

    // Search on Enter key
    dlNoInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchBtn.click();
        }
    });

    // Search button click
    searchBtn.addEventListener('click', function() {
        const dlNo = dlNoInput.value.trim();
        if (dlNo) {
            window.location.href = '{{ route("collector.collection.create") }}?dl_no=' + encodeURIComponent(dlNo);
        }
    });

    // QR Code Scanner (using device camera)
    scanQrBtn.addEventListener('click', function() {
        // Simple implementation - you can integrate a QR scanner library like html5-qrcode
        alert('QR Scanner: Please install a QR scanner library like html5-qrcode for full functionality. For now, you can manually enter the DL number.');
    });

    // Auto-focus on amount field if party is found
    @if($party)
        document.getElementById('amount_collected').focus();
        document.getElementById('amount_collected').select();
    @endif
});
</script>
@endpush
@endsection

