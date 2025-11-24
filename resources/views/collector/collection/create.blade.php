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
                        @csrf
                        <div class="input-group input-group-lg mb-3">
                            <span class="input-group-text"><i class="bi bi-qr-code"></i></span>
                            <input type="text" 
                                   class="form-control" 
                                   id="dl_no_input" 
                                   name="dl_no"
                                   placeholder="Scan QR code or type DL number"
                                   value="{{ old('dl_no', $dlNo ?? '') }}"
                                   autofocus
                                   autocapitalize="off"
                                   spellcheck="false">
                            <button class="btn btn-primary" type="button" id="scan_qr_btn">
                                <i class="bi bi-camera-fill"></i> <span class="d-none d-sm-inline">Scan QR</span>
                            </button>
                            <button class="btn btn-success" type="button" id="search_btn">
                                <i class="bi bi-search"></i> <span class="d-none d-sm-inline">Search</span>
                            </button>
                        </div>
                    </form>

                    <!-- QR Scanner Modal -->
                    <div id="qr-reader-modal" class="modal-backdrop fade" style="display:none; z-index: 1055;">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header bg-dark text-white">
                                    <h5 class="modal-title"><i class="bi bi-camera"></i> Scan QR Code</h5>
                                    <button type="button" id="close-qr-btn" class="btn-close btn-close-white" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-0">
                                    <div id="qr-reader" style="width:100%;"></div>
                                    <div id="qr-reader-results"></div>
                                </div>
                                <div class="modal-footer">
                                    <small class="text-muted">Position the QR code inside the frame</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <small class="text-muted d-block">Tip: Tap the camera button to scan QR code from customer's ID</small>
                </div>

                <!-- Party Details -->
                @if($party)
                <div class="alert alert-success border-start border-success border-5">
                    <h5 class="alert-heading"><i class="bi bi-person-check"></i> Party Found!</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Name:</strong> {{ $party->name }}<br>
                            <strong>Store:</strong> {{ $party->store_name ?? '—' }}<br>
                            <strong>Phone:</strong> {{ $party->phone ?? '—' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Loan Amount:</strong> ₹{{ number_format($party->loan_amount, 2) }}<br>
                            <strong>Interest:</strong> ₹{{ number_format($party->interest_amount ?? 0, 2) }}<br>
                            <strong>Daily Due:</strong> <span class="text-primary fw-bold">₹{{ number_format($party->daily_amount_display ?? 0, 2) }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <small class="text-muted">Collected</small><br>
                            <strong class="text-success">₹{{ number_format($party->collected_amount ?? 0, 2) }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Remaining</small><br>
                            <strong class="text-danger">₹{{ number_format($party->remaining_amount ?? 0, 2) }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Collection Form -->
                <form action="{{ route('collector.collection.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="dl_no" value="{{ $party->dl_no }}">

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="date" class="form-label">Collection Date <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('date') is-invalid @enderror" 
                                   id="date" 
                                   name="date" 
                                   value="{{ old('date', date('Y-m-d')) }}" 
                                   required>
                            @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="amount_collected" class="form-label">Amount Collected <span class="text-danger">*</span></label>
                            <input type="number" 
                                   step="0.01" 
                                   min="0.01"
                                   class="form-control form-control-lg @error('amount_collected') is-invalid @enderror" 
                                   id="amount_collected" 
                                   name="amount_collected" 
                                   value="{{ old('amount_collected', $party->daily_amount_display ?? '') }}" 
                                   required>
                            @error('amount_collected') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-success">Suggested: ₹{{ number_format($party->daily_amount_display ?? 0, 2) }}</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks (Optional)</label>
                        <textarea class="form-control @error('remarks') is-invalid @enderror" 
                                  id="remarks" 
                                  name="remarks" 
                                  rows="3"
                                  placeholder="e.g., Paid in cash, customer was cooperative...">{{ old('remarks') }}</textarea>
                        @error('remarks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('collector.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-success btn-lg px-5">
                            <i class="bi bi-check-circle-fill"></i> Complete Collection
                        </button>
                    </div>
                </form>

                @elseif($dlNo)
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle"></i> No active loan found for DL Number: <strong>{{ $dlNo }}</strong>
                    <br><small>Please check the number or contact admin.</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- HTML5 QR Code Scanner Library -->
@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dlNoInput = document.getElementById('dl_no_input');
    const searchBtn = document.getElementById('search_btn');
    const scanQrBtn = document.getElementById('scan_qr_btn');
    const qrModal = document.getElementById('qr-reader-modal');
    const qrReaderDiv = document.getElementById('qr-reader');
    const closeQrBtn = document.getElementById('close-qr-btn');
    const searchForm = document.getElementById('search_form');

    let html5QrCode = null;

    // Submit form on Enter
    dlNoInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchForm.submit();
        }
    });

    // Manual search button
    searchBtn.addEventListener('click', () => searchForm.submit());

    // Open QR Scanner
    scanQrBtn.addEventListener('click', function () {
        qrModal.style.display = 'block';
        qrReaderDiv.innerHTML = '';

        html5QrCode = new Html5Qrcode("qr-reader");

        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1,
            facingMode: "environment" // Rear camera
        };

        html5QrCode.start(
            config,
            config,
            (decodedText) => {
                dlNoInput.value = decodedText.trim();
                stopScannerAndClose();
                setTimeout(() => searchForm.submit(), 300);
            },
            (error) => {
                // Silently ignore scan errors (normal during scanning)
                // console.warn(error);
            }
        ).catch(err => {
            alert("Camera access denied or not available. Please allow camera permission.");
            console.error("QR Scanner Error:", err);
            qrModal.style.display = 'none';
        });
    });

    // Stop scanner and close modal
    function stopScannerAndClose() {
        if (html5QrCode && html5QrCode.getState() === Html5QrcodeScannerState.SCANNING) {
            html5QrCode.stop().then(() => {
                qrModal.style.display = 'none';
            }).catch(err => console.error("Stop error:", err));
        } else {
            qrModal.style.display = 'none';
        }
    }

    // Close button
    closeQrBtn.addEventListener('click', stopScannerAndClose);

    // Click outside to close
    qrModal.addEventListener('click', function (e) {
        if (e.target === qrModal) {
            stopScannerAndClose();
        }
    });

    // Auto-focus amount field when party is loaded
    @if($party)
        setTimeout(() => {
            const amountField = document.getElementById('amount_collected');
            if (amountField) {
                amountField.focus();
                amountField.select();
            }
        }, 100);
    @endif
});
</script>

<style>
    #qr-reader {
        width: 100% !important;
    }
    #qr-reader video {
        border-radius: 8px;
    }
    .modal-backdrop {
        background-color: rgba(0,0,0,0.85);
    }
    @media (max-width: 576px) {
        #qr-reader-modal .modal-dialog {
            margin: 1rem;
        }
    }
</style>
@endpush

@endsection