@extends('layouts.app')
@section('title', 'Issue Certificate')
@section('page-title', 'Issue Certificate')
@section('breadcrumb', 'Certificates > Issue New')

@section('content')
<div class="card">
    <div class="card-header py-3"><i class="bi bi-file-earmark-plus me-1"></i> Issue New Certificate</div>
    <div class="card-body">
        <form action="{{ route('certificates.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Resident <span class="text-danger">*</span></label>
                    <select name="resident_id" class="form-select" required>
                        <option value="">-- Select Resident --</option>
                        @foreach($residents as $resident)
                            <option value="{{ $resident->id }}" {{ old('resident_id') == $resident->id ? 'selected' : '' }}>{{ $resident->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Certificate Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        <option value="">-- Select Type --</option>
                        @foreach(['Barangay Clearance','Certificate of Residency','Certificate of Indigency','Business Clearance','Barangay ID'] as $type)
                            <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Purpose <span class="text-danger">*</span></label>
                    <input type="text" name="purpose" class="form-control" value="{{ old('purpose') }}" required placeholder="e.g., Employment, Travel, etc.">
                </div>
                <div class="col-md-3">
                    <label class="form-label">OR Number</label>
                    <input type="text" name="or_number" class="form-control" value="{{ old('or_number') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Amount (₱) <span class="text-danger">*</span></label>
                    <input type="number" name="amount" class="form-control" value="{{ old('amount', 0) }}" step="0.01" min="0" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date Issued <span class="text-danger">*</span></label>
                    <input type="date" name="date_issued" class="form-control" value="{{ old('date_issued', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Valid Until</label>
                    <input type="date" name="valid_until" class="form-control" value="{{ old('valid_until') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Remarks</label>
                    <textarea name="remarks" class="form-control" rows="1">{{ old('remarks') }}</textarea>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('certificates.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Issue Certificate</button>
            </div>
        </form>
    </div>
</div>
@endsection
