@extends('layouts.app')
@section('title', 'Edit Announcement')
@section('page-title', 'Edit Announcement')
@section('breadcrumb', 'Announcements > Edit')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Edit Announcement</h5>
    <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('announcements.update', $announcement) }}">
            @csrf
            @method('PUT')
            @include('announcements._form')
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-1"></i> Update Announcement
            </button>
        </form>
    </div>
</div>
@endsection
