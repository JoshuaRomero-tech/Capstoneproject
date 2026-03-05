@extends('layouts.public')
@section('title', 'Services - Barangay Profiling System')

@section('content')
<div class="page-banner">
    <div class="container">
        <h2><i class="bi bi-grid-fill me-2"></i>Barangay Services</h2>
        <p>Access barangay services online — submit requests from the comfort of your home</p>
    </div>
</div>

<div class="container py-5">
    {{-- Success message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Certificate Services --}}
        <div class="col-md-6">
            <div class="service-card text-start h-100">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="service-icon flex-shrink-0" style="background: rgba(79,70,229,0.1); color: #4f46e5; margin: 0;">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Certificate Requests</h5>
                        <p class="mb-0">Request official barangay documents and certificates online.</p>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <h6 class="fw-600 text-muted small text-uppercase mb-2">Available Certificates</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex align-items-center gap-2 py-1">
                            <i class="bi bi-check-circle-fill text-success small"></i>
                            <span class="small">Barangay Clearance</span>
                        </li>
                        <li class="d-flex align-items-center gap-2 py-1">
                            <i class="bi bi-check-circle-fill text-success small"></i>
                            <span class="small">Certificate of Residency</span>
                        </li>
                        <li class="d-flex align-items-center gap-2 py-1">
                            <i class="bi bi-check-circle-fill text-success small"></i>
                            <span class="small">Certificate of Indigency</span>
                        </li>
                        <li class="d-flex align-items-center gap-2 py-1">
                            <i class="bi bi-check-circle-fill text-success small"></i>
                            <span class="small">Business Clearance</span>
                        </li>
                        <li class="d-flex align-items-center gap-2 py-1">
                            <i class="bi bi-check-circle-fill text-success small"></i>
                            <span class="small">Barangay ID</span>
                        </li>
                    </ul>
                </div>
                <a href="{{ route('public.certificate-request') }}" class="btn btn-primary w-100">
                    <i class="bi bi-file-earmark-plus me-1"></i> Request a Certificate
                </a>
            </div>
        </div>

        {{-- Blotter Services --}}
        <div class="col-md-6">
            <div class="service-card text-start h-100">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="service-icon flex-shrink-0" style="background: rgba(245,158,11,0.1); color: #f59e0b; margin: 0;">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">File a Blotter Report</h5>
                        <p class="mb-0">Report incidents and complaints for official documentation.</p>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <h6 class="fw-600 text-muted small text-uppercase mb-2">What You Need to Know</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex align-items-center gap-2 py-1">
                            <i class="bi bi-info-circle-fill text-info small"></i>
                            <span class="small">Both complainant and respondent must be registered residents</span>
                        </li>
                        <li class="d-flex align-items-center gap-2 py-1">
                            <i class="bi bi-info-circle-fill text-info small"></i>
                            <span class="small">Provide complete details of the incident</span>
                        </li>
                        <li class="d-flex align-items-center gap-2 py-1">
                            <i class="bi bi-info-circle-fill text-info small"></i>
                            <span class="small">A case number will be assigned automatically</span>
                        </li>
                        <li class="d-flex align-items-center gap-2 py-1">
                            <i class="bi bi-info-circle-fill text-info small"></i>
                            <span class="small">Visit the barangay hall for mediation proceedings</span>
                        </li>
                        <li class="d-flex align-items-center gap-2 py-1">
                            <i class="bi bi-info-circle-fill text-info small"></i>
                            <span class="small">Reports are reviewed by barangay staff</span>
                        </li>
                    </ul>
                </div>
                <a href="{{ route('public.blotter-file') }}" class="btn btn-warning w-100 text-dark">
                    <i class="bi bi-journal-plus me-1"></i> File a Blotter Report
                </a>
            </div>
        </div>
    </div>

    {{-- How it Works --}}
    <div class="mt-5">
        <h4 class="fw-700 text-center mb-4">How It Works</h4>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-3">
                    <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(79,70,229,0.1); color: #4f46e5; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 800; margin: 0 auto 1rem;">
                        1
                    </div>
                    <h6 class="fw-700">Submit Your Request</h6>
                    <p class="text-muted small">Fill out the online form with the required information and submit.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-3">
                    <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(245,158,11,0.1); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 800; margin: 0 auto 1rem;">
                        2
                    </div>
                    <h6 class="fw-700">Staff Reviews</h6>
                    <p class="text-muted small">Barangay staff processes and reviews your request at the office.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-3">
                    <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(16,185,129,0.1); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 800; margin: 0 auto 1rem;">
                        3
                    </div>
                    <h6 class="fw-700">Visit & Claim</h6>
                    <p class="text-muted small">Visit the barangay hall to pay any fees and claim your document.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
