@extends('layouts.app')
@section('title', 'Add Resident')
@section('page-title', 'Add New Resident')
@section('breadcrumb', 'Residents > Add New')

@section('content')
<div class="card">
    <div class="card-header py-3">
        <i class="bi bi-person-plus me-1"></i> Resident Information Form
    </div>
    <div class="card-body">
        <form action="{{ route('residents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('residents._form')
            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('residents.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Resident</button>
            </div>
        </form>
    </div>
</div>
@endsection
