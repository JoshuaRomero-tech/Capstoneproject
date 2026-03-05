@extends('layouts.app')
@section('title', 'Edit Household')
@section('page-title', 'Edit Household')
@section('breadcrumb', 'Households > Edit')

@section('content')
<div class="card">
    <div class="card-header py-3"><i class="bi bi-pencil-square me-1"></i> Edit Household: {{ $household->household_no }}</div>
    <div class="card-body">
        <form action="{{ route('households.update', $household) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Household No. <span class="text-danger">*</span></label>
                    <input type="text" name="household_no" class="form-control" value="{{ old('household_no', $household->household_no) }}" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Address <span class="text-danger">*</span></label>
                    <textarea name="address" class="form-control" rows="2" required>{{ old('address', $household->address) }}</textarea>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('households.show', $household) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
