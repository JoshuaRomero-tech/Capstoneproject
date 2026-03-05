@extends('layouts.public')
@section('title', 'Request Certificate - Barangay Profiling System')

@section('content')
<div class="page-banner">
    <div class="container">
        <h2><i class="bi bi-file-earmark-text-fill me-2"></i>Request a Certificate</h2>
        <p>Submit your certificate request online for faster processing</p>
    </div>
</div>

<div class="container py-4">
    <div class="mb-3">
        <a href="{{ route('public.services') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Services
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <i class="bi bi-pencil-square me-2"></i>Certificate Request Form
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('public.certificate-request.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Select Resident <span class="text-danger">*</span></label>
                            <select name="resident_id" class="form-select @error('resident_id') is-invalid @enderror" required>
                                <option value="">-- Select your name --</option>
                                @foreach($residents as $resident)
                                    <option value="{{ $resident->id }}" {{ old('resident_id', request('resident_id')) == $resident->id ? 'selected' : '' }}>
                                        {{ $resident->full_name }} — {{ $resident->address }}
                                    </option>
                                @endforeach
                            </select>
                            @error('resident_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">If your name is not listed, please visit the barangay hall to register first.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Certificate Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">-- Select certificate type --</option>
                                <option value="Barangay Clearance" {{ old('type') == 'Barangay Clearance' ? 'selected' : '' }}>Barangay Clearance</option>
                                <option value="Certificate of Residency" {{ old('type') == 'Certificate of Residency' ? 'selected' : '' }}>Certificate of Residency</option>
                                <option value="Certificate of Indigency" {{ old('type') == 'Certificate of Indigency' ? 'selected' : '' }}>Certificate of Indigency</option>
                                <option value="Business Clearance" {{ old('type') == 'Business Clearance' ? 'selected' : '' }}>Business Clearance</option>
                                <option value="Barangay ID" {{ old('type') == 'Barangay ID' ? 'selected' : '' }}>Barangay ID</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Purpose <span class="text-danger">*</span></label>
                            <input type="text" name="purpose" class="form-control @error('purpose') is-invalid @enderror"
                                   value="{{ old('purpose') }}" required placeholder="e.g., Employment, School requirements, Travel, etc.">
                            @error('purpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info py-2 mb-4">
                            <small>
                                <i class="bi bi-info-circle me-1"></i>
                                <strong>Note:</strong> After submitting, please visit the barangay hall to pay the processing fee and claim your certificate. Bring a valid ID for verification.
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-send me-1"></i> Submit Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
