@extends('layouts.app')
@section('title', 'Blotter ' . $blotter->case_no)
@section('page-title', 'Blotter Report Details')
@section('breadcrumb', 'Blotter Reports > Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('blotters.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
    @if($blotter->status === 'Pending' || $blotter->status === 'Ongoing')
        <a href="{{ route('blotters.review', $blotter) }}" class="btn btn-primary btn-sm"><i class="bi bi-clipboard-check me-1"></i> Review & Update Status</a>
    @endif
</div>

{{-- Status Banner --}}
@php $statusColors = ['Pending' => 'warning', 'Ongoing' => 'info', 'Resolved' => 'success', 'Dismissed' => 'secondary']; @endphp
<div class="alert alert-{{ $statusColors[$blotter->status] ?? 'secondary' }} d-flex align-items-center mb-3" role="alert">
    <i class="bi bi-journal-text fs-4 me-2"></i>
    <div>
        <strong>Case: {{ $blotter->case_no }}</strong>
        <span class="badge bg-{{ $statusColors[$blotter->status] ?? 'secondary' }} ms-2">{{ $blotter->status }}</span>
    </div>
</div>

<div class="card">
    <div class="card-header py-3"><i class="bi bi-journal-text me-1"></i> Report Information</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <small class="text-muted">Complainant</small>
                        <p class="fw-bold fs-5 mb-0">
                            <a href="{{ route('residents.show', $blotter->complainant) }}" class="text-decoration-none">
                                {{ $blotter->complainant->full_name }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <small class="text-muted">Respondent</small>
                        <p class="fw-bold fs-5 mb-0">
                            <a href="{{ route('residents.show', $blotter->respondent) }}" class="text-decoration-none">
                                {{ $blotter->respondent->full_name }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <small class="text-muted">Incident Type</small>
                <p class="fw-semibold">{{ $blotter->incident_type }}</p>
            </div>
            <div class="col-md-4">
                <small class="text-muted">Incident Date</small>
                <p class="fw-semibold">{{ $blotter->incident_date->format('F d, Y') }}</p>
            </div>
            <div class="col-md-4">
                <small class="text-muted">Incident Location</small>
                <p class="fw-semibold">{{ $blotter->incident_location }}</p>
            </div>
            <div class="col-12">
                <small class="text-muted">Incident Details</small>
                <p class="fw-semibold">{{ $blotter->incident_details }}</p>
            </div>
            @if($blotter->action_taken)
            <div class="col-12">
                <small class="text-muted">Action Taken</small>
                <p class="fw-semibold">{{ $blotter->action_taken }}</p>
            </div>
            @endif
            @if($blotter->remarks)
            <div class="col-12">
                <small class="text-muted">Remarks</small>
                <p class="fw-semibold">{{ $blotter->remarks }}</p>
            </div>
            @endif
            <div class="col-12">
                <small class="text-muted">Recorded By</small>
                <p class="fw-semibold">{{ $blotter->recordedBy->name }} | {{ $blotter->created_at->format('F d, Y h:i A') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
