@extends('layouts.app')
@section('title', 'Certificate Details')
@section('page-title', 'Certificate Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('certificates.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
    <a href="{{ route('certificates.print', $certificate) }}" class="btn btn-success btn-sm" target="_blank"><i class="bi bi-printer me-1"></i> Print</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <small class="text-muted">Resident</small>
                <p class="fw-bold fs-5">{{ $certificate->resident->full_name }}</p>
            </div>
            <div class="col-md-6">
                <small class="text-muted">Certificate Type</small>
                <p><span class="badge bg-info fs-6">{{ $certificate->type }}</span></p>
            </div>
            <div class="col-md-4">
                <small class="text-muted">Purpose</small>
                <p class="fw-semibold">{{ $certificate->purpose }}</p>
            </div>
            <div class="col-md-4">
                <small class="text-muted">OR Number</small>
                <p class="fw-semibold">{{ $certificate->or_number ?? 'N/A' }}</p>
            </div>
            <div class="col-md-4">
                <small class="text-muted">Amount</small>
                <p class="fw-semibold">₱{{ number_format($certificate->amount, 2) }}</p>
            </div>
            <div class="col-md-4">
                <small class="text-muted">Date Issued</small>
                <p class="fw-semibold">{{ $certificate->date_issued->format('F d, Y') }}</p>
            </div>
            <div class="col-md-4">
                <small class="text-muted">Valid Until</small>
                <p class="fw-semibold">{{ $certificate->valid_until ? $certificate->valid_until->format('F d, Y') : 'N/A' }}</p>
            </div>
            <div class="col-md-4">
                <small class="text-muted">Issued By</small>
                <p class="fw-semibold">{{ $certificate->issuedBy->name }}</p>
            </div>
            @if($certificate->remarks)
            <div class="col-12">
                <small class="text-muted">Remarks</small>
                <p class="fw-semibold">{{ $certificate->remarks }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
