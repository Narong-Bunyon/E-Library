@extends('layouts.admin')

@section('title', 'Share Report - E-Library')

@section('page-title', 'Share Report')

@section('content')
<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Share Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-share-alt me-2"></i>
                        Share {{ $reportData['title'] }}
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Report Info -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i>
                            <div>
                                <strong>{{ $reportData['title'] }}</strong><br>
                                <small class="text-muted">Generated on {{ now()->format('M d, Y H:i') }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Share Options -->
                    <div class="mb-4">
                        <h6 class="mb-3">Share Options</h6>
                        
                        <!-- Direct Link -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Direct Link</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $shareUrl }}" readonly id="shareUrl">
                                <button class="btn btn-outline-primary" onclick="copyToClipboard('shareUrl')">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </div>
                            <small class="text-muted">Share this link with others to view the report</small>
                        </div>

                        <!-- Embed Code -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Embed Code</label>
                            <div class="input-group">
                                <textarea class="form-control" rows="3" readonly id="embedCode"><iframe src="{{ $shareUrl }}" width="100%" height="600" frameborder="0"></iframe></textarea>
                                <button class="btn btn-outline-primary" onclick="copyToClipboard('embedCode')">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </div>
                            <small class="text-muted">Embed this report in other websites</small>
                        </div>

                        <!-- Email Share -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email Share</label>
                            <div class="d-flex gap-2">
                                <input type="email" class="form-control" placeholder="Enter email address" id="shareEmail">
                                <button class="btn btn-primary" onclick="shareViaEmail()">
                                    <i class="fas fa-envelope me-1"></i> Send Email
                                </button>
                            </div>
                            <small class="text-muted">Send report link via email</small>
                        </div>
                    </div>

                    <!-- Social Media Share -->
                    <div class="mb-4">
                        <h6 class="mb-3">Social Media</h6>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-facebook-f me-1"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode('Check out this ' . $reportData['title']) }}" target="_blank" class="btn btn-outline-info btn-sm">
                                <i class="fab fa-twitter me-1"></i> Twitter
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($shareUrl) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-linkedin-in me-1"></i> LinkedIn
                            </a>
                            <button class="btn btn-outline-success btn-sm" onclick="shareViaWhatsApp()">
                                <i class="fab fa-whatsapp me-1"></i> WhatsApp
                            </button>
                        </div>
                    </div>

                    <!-- QR Code -->
                    <div class="mb-4">
                        <h6 class="mb-3">QR Code</h6>
                        <div class="text-center">
                            <div class="qr-code-placeholder bg-light p-4 rounded d-inline-block">
                                <i class="fas fa-qrcode fa-3x text-muted"></i>
                                <p class="text-muted mb-0 mt-2">QR Code for {{ $shareUrl }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Report Statistics -->
                    @if(!empty($reportData['stats']))
                    <div class="mb-4">
                        <h6 class="mb-3">Report Summary</h6>
                        <div class="row">
                            @foreach($reportData['stats'] as $key => $value)
                            <div class="col-md-4 mb-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                                    <strong>{{ number_format($value) }}</strong>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('reports.view', $id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Report
                        </a>
                        <a href="{{ route('reports') }}" class="btn btn-primary">
                            <i class="fas fa-list me-1"></i> All Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    document.execCommand('copy');
    
    // Show success message
    const button = element.nextElementSibling;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-check me-1"></i> Copied!';
    button.classList.remove('btn-outline-primary');
    button.classList.add('btn-success');
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.classList.remove('btn-success');
        button.classList.add('btn-outline-primary');
    }, 2000);
}

function shareViaEmail() {
    const email = document.getElementById('shareEmail').value;
    if (!email) {
        alert('Please enter an email address');
        return;
    }
    
    const subject = encodeURIComponent('{{ $reportData["title"] }}');
    const body = encodeURIComponent(`Check out this report: {{ $shareUrl }}`);
    
    window.location.href = `mailto:${email}?subject=${subject}&body=${body}`;
}

function shareViaWhatsApp() {
    const text = encodeURIComponent(`Check out this {{ $reportData["title"] }}: {{ $shareUrl }}`);
    window.open(`https://wa.me/?text=${text}`, '_blank');
}
</script>
@endpush
