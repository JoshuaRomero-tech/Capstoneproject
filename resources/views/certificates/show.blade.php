@extends('layouts.app')
@section('title', 'Certificate Request Details')
@section('page-title', 'Certificate Request Details')
@section('breadcrumb', 'Certificate Requests > Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('certificates.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
    @if($certificate->status === 'Approved')
        <a href="{{ route('certificates.print', $certificate) }}" class="btn btn-success btn-sm" target="_blank"><i class="bi bi-printer me-1"></i> Print Certificate</a>
    @endif
</div>

{{-- Status Banner --}}
@php
    $statusColors = ['Pending' => 'warning', 'Approved' => 'success', 'Disapproved' => 'danger'];
    $statusIcons = ['Pending' => 'hourglass-split', 'Approved' => 'check-circle-fill', 'Disapproved' => 'x-circle-fill'];
@endphp
<div class="alert alert-{{ $statusColors[$certificate->status] ?? 'secondary' }} d-flex align-items-center mb-3" role="alert">
    <i class="bi bi-{{ $statusIcons[$certificate->status] ?? 'question' }} fs-4 me-2"></i>
    <div>
        <strong>Status: {{ $certificate->status }}</strong>
        @if($certificate->reviewed_at)
            <span class="ms-2">— Reviewed by {{ $certificate->reviewedBy->name }} on {{ $certificate->reviewed_at->format('M d, Y h:i A') }}</span>
        @endif
    </div>
</div>

{{-- Request Details --}}
<div class="card mb-3">
    <div class="card-header py-3"><i class="bi bi-file-earmark-text me-1"></i> Request Information</div>
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
                <small class="text-muted">Date Requested</small>
                <p class="fw-semibold">{{ $certificate->created_at->format('F d, Y h:i A') }}</p>
            </div>
            <div class="col-md-4">
                <small class="text-muted">Source</small>
                <p class="fw-semibold">{{ $certificate->remarks === 'Requested via public portal - pending processing' ? 'Public Portal' : 'Walk-in' }}</p>
            </div>
            @if($certificate->status === 'Approved')
            <div class="col-md-4">
                <small class="text-muted">OR Number</small>
                <p class="fw-semibold">{{ $certificate->or_number ?? 'N/A' }}</p>
            </div>
            <div class="col-md-4">
                <small class="text-muted">Amount</small>
                <p class="fw-semibold">₱{{ number_format($certificate->amount, 2) }}</p>
            </div>
            <div class="col-md-4">
                <small class="text-muted">Valid Until</small>
                <p class="fw-semibold">{{ $certificate->valid_until ? $certificate->valid_until->format('F d, Y') : 'N/A' }}</p>
            </div>
            @endif
            @if($certificate->review_remarks)
            <div class="col-12">
                <small class="text-muted">Review Remarks</small>
                <p class="fw-semibold">{{ $certificate->review_remarks }}</p>
            </div>
            @endif
            @if($certificate->remarks && $certificate->remarks !== 'Requested via public portal - pending processing')
            <div class="col-12">
                <small class="text-muted">Remarks</small>
                <p class="fw-semibold">{{ $certificate->remarks }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Approve / Disapprove Actions (only for Pending) --}}
@if($certificate->status === 'Pending')
<div class="row g-3">
    {{-- Approve Form --}}
    <div class="col-md-6">
        <div class="card border-success">
            <div class="card-header bg-success bg-opacity-10 text-success py-3">
                <i class="bi bi-check-circle me-1"></i> Approve Request
            </div>
            <div class="card-body">
                <form action="{{ route('certificates.approve', $certificate) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">OR Number</label>
                        <input type="text" name="or_number" class="form-control" placeholder="e.g., OR-2024-001">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount (₱) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" value="0" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Valid Until</label>
                        <input type="date" name="valid_until" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Review Remarks</label>
                        <textarea name="review_remarks" class="form-control" rows="2" placeholder="Optional remarks..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Approve this certificate request?')">
                        <i class="bi bi-check-lg me-1"></i> Approve Certificate
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Disapprove Form --}}
    <div class="col-md-6">
        <div class="card border-danger">
            <div class="card-header bg-danger bg-opacity-10 text-danger py-3">
                <i class="bi bi-x-circle me-1"></i> Disapprove Request
            </div>
            <div class="card-body">
                <form action="{{ route('certificates.disapprove', $certificate) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Reason for Disapproval <span class="text-danger">*</span></label>
                        <textarea name="review_remarks" class="form-control" rows="6" required placeholder="Please provide a reason for disapproval..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Disapprove this certificate request?')">
                        <i class="bi bi-x-lg me-1"></i> Disapprove Certificate
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
