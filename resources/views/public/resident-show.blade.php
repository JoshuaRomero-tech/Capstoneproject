@extends('layouts.public')
@section('title', $resident->full_name . ' - CiviTrack')

@section('content')
<div class="page-banner">
    <div class="container">
        <h2><i class="bi bi-person-fill me-2"></i>Resident Profile</h2>
        <p>Viewing public information for {{ $resident->full_name }}</p>
    </div>
</div>

<div class="container py-4">
    <div class="mb-3">
        <a href="{{ route('public.residents') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Directory
        </a>
    </div>

    <div class="row g-4">
        {{-- Profile Card --}}
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body py-4">
                    <div style="width: 90px; height: 90px; border-radius: 50%; background: linear-gradient(135deg, #4f46e5, #818cf8); display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 700; color: #fff; margin: 0 auto 1rem;">
                        {{ strtoupper(substr($resident->first_name, 0, 1)) }}{{ strtoupper(substr($resident->last_name, 0, 1)) }}
                    </div>
                    <h5 class="fw-700 mb-1">{{ $resident->full_name }}</h5>
                    <span class="badge {{ $resident->gender == 'Male' ? 'text-primary' : 'text-danger' }}" style="background: {{ $resident->gender == 'Male' ? 'rgba(79,70,229,0.1)' : 'rgba(244,63,94,0.1)' }};">
                        <i class="bi bi-gender-{{ strtolower($resident->gender) }} me-1"></i>{{ $resident->gender }}
                    </span>
                    <div class="mt-3">
                        <span class="badge bg-{{ $resident->status == 'Active' ? 'success' : ($resident->status == 'Inactive' ? 'warning' : 'secondary') }} fs-6">
                            {{ $resident->status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-info-circle me-2"></i>Personal Information
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="text-muted small text-uppercase fw-600 mb-1">Age</div>
                            <div class="fw-semibold">{{ $resident->age }} years old</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small text-uppercase fw-600 mb-1">Date of Birth</div>
                            <div class="fw-semibold">{{ $resident->date_of_birth->format('F d, Y') }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small text-uppercase fw-600 mb-1">Civil Status</div>
                            <div class="fw-semibold">{{ $resident->civil_status }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small text-uppercase fw-600 mb-1">Nationality</div>
                            <div class="fw-semibold">{{ $resident->nationality ?? 'Filipino' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small text-uppercase fw-600 mb-1">Religion</div>
                            <div class="fw-semibold">{{ $resident->religion ?? 'N/A' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small text-uppercase fw-600 mb-1">Occupation</div>
                            <div class="fw-semibold">{{ $resident->occupation ?? 'N/A' }}</div>
                        </div>
                        <div class="col-12">
                            <div class="text-muted small text-uppercase fw-600 mb-1">Address</div>
                            <div class="fw-semibold">{{ $resident->address }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card mt-3">
                <div class="card-header">
                    <i class="bi bi-lightning me-2"></i>Quick Actions
                </div>
                <div class="card-body">
                    <a href="{{ route('public.certificate-request', ['resident_id' => $resident->id]) }}" class="btn btn-primary me-2">
                        <i class="bi bi-file-earmark-text me-1"></i> Request Certificate
                    </a>
                    <a href="{{ route('public.blotter-file', ['complainant_id' => $resident->id]) }}" class="btn btn-outline-primary">
                        <i class="bi bi-journal-text me-1"></i> File Blotter
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
