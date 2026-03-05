@extends('layouts.app')
@section('title', 'Edit Resident')
@section('page-title', 'Edit Resident')

@section('content')
<div class="card">
    <div class="card-header py-3">
        <i class="bi bi-pencil-square me-1"></i> Edit Resident: {{ $resident->full_name }}
    </div>
    <div class="card-body">
        <form action="{{ route('residents.update', $resident) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('residents._form', ['editing' => true])
            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('residents.show', $resident) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Update Resident</button>
            </div>
        </form>
    </div>
</div>
@endsection
