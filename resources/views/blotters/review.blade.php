@extends('layouts.app')
@section('title', 'Review Blotter')
@section('page-title', 'Review Blotter Report')
@section('breadcrumb', 'Blotter Reports > Review')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('blotters.show', $blotter) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to Details</a>
</div>

{{-- Case Summary --}}
@php $statusColors = ['Pending' => 'warning', 'Ongoing' => 'info', 'Resolved' => 'success', 'Dismissed' => 'secondary']; @endphp
<div class="alert alert-{{ $statusColors[$blotter->status] ?? 'secondary' }} d-flex align-items-center mb-3" role="alert">
    <i class="bi bi-journal-text fs-4 me-2"></i>
    <div>
        <strong>Case: {{ $blotter->case_no }}</strong>
        <span class="badge bg-{{ $statusColors[$blotter->status] ?? 'secondary' }} ms-2">Current: {{ $blotter->status }}</span>
        <span class="ms-2">| {{ $blotter->complainant->full_name }} vs. {{ $blotter->respondent->full_name }}</span>
        <span class="ms-2">| {{ $blotter->incident_type }} — {{ $blotter->incident_date->format('M d, Y') }}</span>
    </div>
</div>

{{-- Incident Details Summary --}}
<div class="card mb-3">
    <div class="card-header py-2"><i class="bi bi-info-circle me-1"></i> Incident Summary</div>
    <div class="card-body">
        <p class="mb-0">{{ $blotter->incident_details }}</p>
    </div>
</div>

{{-- Review Form --}}
<div class="card">
    <div class="card-header py-3"><i class="bi bi-clipboard-check me-1"></i> Update Status</div>
    <div class="card-body">
        <form action="{{ route('blotters.update-status', $blotter) }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">New Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach(['Pending','Ongoing','Resolved','Dismissed'] as $status)
                            <option value="{{ $status }}" {{ old('status', $blotter->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">
                        <span class="badge bg-warning">Pending</span> New report awaiting action<br>
                        <span class="badge bg-info">Ongoing</span> Under investigation/mediation<br>
                        <span class="badge bg-success">Resolved</span> Case settled<br>
                        <span class="badge bg-secondary">Dismissed</span> Case dismissed
                    </div>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Action Taken</label>
                    <textarea name="action_taken" class="form-control" rows="3" placeholder="Describe the action taken on this case...">{{ old('action_taken', $blotter->action_taken) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Remarks</label>
                    <textarea name="remarks" class="form-control" rows="2" placeholder="Additional notes or remarks...">{{ old('remarks', $blotter->remarks) }}</textarea>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('blotters.show', $blotter) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary" onclick="return confirm('Update this blotter status?')">
                    <i class="bi bi-check-lg me-1"></i> Update Status
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
