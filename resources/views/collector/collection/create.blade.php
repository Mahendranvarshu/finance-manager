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
                    <form id="search_form" action="{{ route('collector.collection.create') }}" method="GET" autocomplete="off">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text"><i class="bi bi-qr-code"></i></span>
                            <input type="text" 
                                   class="form-control" 
                                   id="dl_no_input" 
                                   name="dl_no"
                                   placeholder="Scan QR code or type DL number"
                                   value="{{ old('dl_no', $dlNo ?? '') }}"
                                   autofocus>
                            <button class="btn btn-primary" type="button" id="scan_qr_btn">
                                <i class="bi bi-camera"></i> Scan QR
                            </button>
                            <button class="btn btn-success" type="submit" id="search_btn">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    </form>

                    <!-- QR Scanner Modal -->
                    <div id="qr-reader-modal" style="display:none; position:fixed; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.7); z-index:1050;">
                        <div style="max-width:400px; margin:5% auto; background:#fff; border-radius:10px; padding:16px; position:relative;">
                            <div id="qr-reader" style="width:100%;"></div>
                            <button type="button" id="close-qr-btn" class="btn btn-danger" style="position:absolute; top:8px; right:8px;">
                                <i class="bi bi-x-lg"></i> Close
                            </button>
                        </div>
                    </div>

                    <small class="text-muted">Enter DL number and click Search to find party details</small>
                </div>

                <!-- Party Details -->
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

<!-- Include HTML5 QR Code library -->
@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let scannerActive = false;

document.getElementById('scan_qr_btn').addEventListener('click', async () => {
    if (scannerActive) return;
    scannerActive = true;

    // Step 1: Force permission request
    let stream;
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } });
    } catch (err) {
        alert("Camera permission denied!\n\nPlease go to browser settings → Site settings → Camera → Allow");
        scannerActive = false;
        return;
    }

    document.getElementById('qr-reader-modal').style.display = 'block';
    const html5QrCode = new Html5Qrcode("qr-reader");

    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 15, qrbox: { width: 300, height: 300 } },
        (decodedText) => {
            document.getElementById('dl_no_input').value = decodedText.trim();
            html5QrCode.stop();
            document.getElementById('qr-reader-modal').style.display = 'none';
            document.getElementById('search_form').submit();
        },
        () => {}
    ).catch(err => {
        alert("Camera failed. Type DL number manually.");
        console.error(err);
    }).finally(() => {
        if (stream) stream.getTracks().forEach(t => t.stop());
        scannerActive = false;
    });
});

// Close modal safely
document.getElementById('close-qr-btn').onclick = () => {
    document.getElementById('qr-reader-modal').style.display = 'none';
    location.reload(); // Simple way to reset camera
};

// Optional: autofocus on amount_collected if party exists
@isset($party)
document.addEventListener('DOMContentLoaded', function() {
    const amountField = document.getElementById('amount_collected');
    if (amountField) {
        amountField.focus();
        amountField.select();
    }
});
@endisset

// Optional: search on Enter key and button
document.getElementById('dl_no_input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('search_form').submit();
    }
});
document.getElementById('search_btn').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('search_form').submit();
});
</script>
@endpush

@endsection
