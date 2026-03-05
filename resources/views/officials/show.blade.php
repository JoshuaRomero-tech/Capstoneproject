@extends('layouts.app')
@section('title', 'Official Details')
@section('page-title', 'Official Details')
@section('breadcrumb', 'Officials > Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('officials.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
    <a href="{{ route('officials.edit', $official) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <small class="text-muted">Name</small>
                <p class="fw-bold fs-5">{{ $official->resident->full_name }}</p>
            </div>
            <div class="col-md-6">
                <small class="text-muted">Position</small>
                <p class="fw-bold fs-5">{{ $official->position }}</p>
            </div>
            <div class="col-md-4">
                <small class="text-muted">Committee</small>
                <p class="fw-semibold">{{ $official->committee ?? 'N/A' }}</p>
            </div>
            <div class="col-md-4">
                <small class="text-muted">Term</small>
                <p class="fw-semibold">{{ $official->term_start->format('M d, Y') }} - {{ $official->term_end->format('M d, Y') }}</p>
            </div>
            <div class="col-md-4">
                <small class="text-muted">Status</small>
                <p><span class="badge bg-{{ $official->status == 'Active' ? 'success' : 'secondary' }} fs-6">{{ $official->status }}</span></p>
            </div>
        </div>
    </div>
</div>
@endsection
