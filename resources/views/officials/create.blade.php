@extends('layouts.app')
@section('title', 'Add Official')
@section('page-title', 'Add Official')

@section('content')
<div class="card">
    <div class="card-header py-3"><i class="bi bi-person-badge me-1"></i> New Barangay Official</div>
    <div class="card-body">
        <form action="{{ route('officials.store') }}" method="POST">
            @csrf
            @include('officials._form')
            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('officials.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
