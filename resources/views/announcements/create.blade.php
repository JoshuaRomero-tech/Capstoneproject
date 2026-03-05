@extends('layouts.app')
@section('title', 'New Announcement')
@section('page-title', 'New Announcement')
@section('breadcrumb', 'Announcements > Create')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Create Announcement</h5>
    <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('announcements.store') }}">
            @csrf
            @include('announcements._form')
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-megaphone me-1"></i> Post Announcement
            </button>
        </form>
    </div>
</div>
@endsection
