@extends('layouts.public')
@section('title', 'File Blotter Report - Barangay Profiling System')

@section('content')
<div class="page-banner">
    <div class="container">
        <h2><i class="bi bi-journal-text me-2"></i>File a Blotter Report</h2>
        <p>Report an incident or file a complaint for official records</p>
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
                    <i class="bi bi-pencil-square me-2"></i>Blotter Report Form
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('public.blotter-file.store') }}">
                        @csrf

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Complainant <span class="text-danger">*</span></label>
                                <select name="complainant_id" class="form-select @error('complainant_id') is-invalid @enderror" required>
                                    <option value="">-- Select complainant --</option>
                                    @foreach($residents as $resident)
                                        <option value="{{ $resident->id }}" {{ old('complainant_id', request('complainant_id')) == $resident->id ? 'selected' : '' }}>
                                            {{ $resident->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('complainant_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Respondent <span class="text-danger">*</span></label>
                                <select name="respondent_id" class="form-select @error('respondent_id') is-invalid @enderror" required>
                                    <option value="">-- Select respondent --</option>
                                    @foreach($residents as $resident)
                                        <option value="{{ $resident->id }}" {{ old('respondent_id') == $resident->id ? 'selected' : '' }}>
                                            {{ $resident->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('respondent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Incident Type <span class="text-danger">*</span></label>
                            <input type="text" name="incident_type" class="form-control @error('incident_type') is-invalid @enderror"
                                   value="{{ old('incident_type') }}" required placeholder="e.g., Physical Assault, Verbal Abuse, Property Damage, Noise Complaint">
                            @error('incident_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Incident Date <span class="text-danger">*</span></label>
                                <input type="date" name="incident_date" class="form-control @error('incident_date') is-invalid @enderror"
                                       value="{{ old('incident_date') }}" required max="{{ date('Y-m-d') }}">
                                @error('incident_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Incident Location <span class="text-danger">*</span></label>
                                <input type="text" name="incident_location" class="form-control @error('incident_location') is-invalid @enderror"
                                       value="{{ old('incident_location') }}" required placeholder="Where did the incident occur?">
                                @error('incident_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Narrative / Details <span class="text-danger">*</span></label>
                            <textarea name="narrative" class="form-control @error('narrative') is-invalid @enderror"
                                      rows="5" required placeholder="Provide a detailed account of what happened...">{{ old('narrative') }}</textarea>
                            @error('narrative')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning py-2 mb-4">
                            <small>
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                <strong>Important:</strong> Filing a false blotter report is punishable by law. Please ensure all information provided is truthful and accurate. After submission, visit the barangay hall for mediation proceedings.
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-send me-1"></i> Submit Blotter Report
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
