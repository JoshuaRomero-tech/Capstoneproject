@extends('layouts.app')
@section('title', 'Add Household')
@section('page-title', 'Add Household')

@section('content')
<div class="card">
    <div class="card-header py-3"><i class="bi bi-house-add me-1"></i> New Household</div>
    <div class="card-body">
        <form action="{{ route('households.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Household No. <span class="text-danger">*</span></label>
                    <input type="text" name="household_no" class="form-control" value="{{ old('household_no') }}" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Address <span class="text-danger">*</span></label>
                    <textarea name="address" class="form-control" rows="2" required>{{ old('address') }}</textarea>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('households.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
