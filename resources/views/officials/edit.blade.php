@extends('layouts.app')
@section('title', 'Edit Official')
@section('page-title', 'Edit Official')

@section('content')
<div class="card">
    <div class="card-header py-3"><i class="bi bi-pencil-square me-1"></i> Edit Official</div>
    <div class="card-body">
        <form action="{{ route('officials.update', $official) }}" method="POST">
            @csrf @method('PUT')
            @include('officials._form')
            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('officials.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
