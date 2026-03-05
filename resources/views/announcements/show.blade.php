@extends('layouts.app')
@section('title', $announcement->title)
@section('page-title', 'Announcement Details')
@section('breadcrumb', 'Announcements > Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Announcement Details</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-start gap-3 mb-4">
            <div>
                @php
                    $priorityColors = ['Normal' => 'primary', 'Important' => 'warning', 'Urgent' => 'danger'];
                    $priorityIcons = ['Normal' => 'info-circle', 'Important' => 'exclamation-circle', 'Urgent' => 'exclamation-triangle'];
                @endphp
                <h4 class="fw-700 mb-1">{{ $announcement->title }}</h4>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-{{ $priorityColors[$announcement->priority] ?? 'secondary' }}">
                        <i class="bi bi-{{ $priorityIcons[$announcement->priority] ?? 'info-circle' }} me-1"></i>
                        {{ $announcement->priority }}
                    </span>
                    @if($announcement->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                    <span class="badge bg-light text-dark">
                        <i class="bi bi-calendar3 me-1"></i>
                        Published: {{ $announcement->publish_date->format('M d, Y') }}
                    </span>
                    @if($announcement->expiry_date)
                        <span class="badge bg-light text-dark">
                            <i class="bi bi-calendar-x me-1"></i>
                            Expires: {{ $announcement->expiry_date->format('M d, Y') }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="border-top pt-3">
            <div style="white-space: pre-wrap; line-height: 1.8; color: #334155;">{{ $announcement->content }}</div>
        </div>

        <div class="border-top mt-4 pt-3 text-muted small">
            <i class="bi bi-person me-1"></i> Posted by <strong>{{ $announcement->postedBy->name }}</strong>
            &middot;
            <i class="bi bi-clock me-1"></i> {{ $announcement->created_at->format('M d, Y h:i A') }}
            @if($announcement->updated_at->ne($announcement->created_at))
                &middot; <i class="bi bi-pencil me-1"></i> Updated {{ $announcement->updated_at->diffForHumans() }}
            @endif
        </div>
    </div>
</div>
@endsection
