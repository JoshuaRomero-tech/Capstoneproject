@extends('layouts.app')
@section('title', $resident->full_name)
@section('page-title', 'Resident Profile')
@section('breadcrumb', 'Residents > Profile')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('residents.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
    <div class="btn-group btn-group-sm">
        <a href="{{ route('residents.edit', $resident) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i> Edit</a>
        <form action="{{ route('residents.destroy', $resident) }}" method="POST" onsubmit="return confirm('Delete this resident?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger"><i class="bi bi-trash me-1"></i> Delete</button>
        </form>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card text-center">
            <div class="card-body py-4">
                @if($resident->photo)
                    <img src="{{ asset('storage/' . $resident->photo) }}" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 120px; height: 120px;">
                        <i class="bi bi-person-fill text-primary" style="font-size: 3rem;"></i>
                    </div>
                @endif
                <h5>{{ $resident->full_name }}</h5>
                <p class="text-muted mb-1">{{ $resident->age }} years old | {{ $resident->gender }}</p>
                @php $statusColors = ['Active' => 'success', 'Inactive' => 'warning', 'Deceased' => 'secondary']; @endphp
                <span class="badge bg-{{ $statusColors[$resident->status] ?? 'secondary' }}">{{ $resident->status }}</span>

                <div class="mt-3">
                    @if($resident->is_pwd) <span class="badge bg-info me-1">PWD</span> @endif
                    @if($resident->is_solo_parent) <span class="badge bg-warning me-1">Solo Parent</span> @endif
                    @if($resident->is_senior_citizen) <span class="badge bg-secondary me-1">Senior Citizen</span> @endif
                    @if($resident->voter_status == 'Registered') <span class="badge bg-success">Registered Voter</span> @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header py-3"><i class="bi bi-info-circle me-1"></i> Personal Details</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted">Date of Birth</small>
                        <p class="mb-2 fw-semibold">{{ $resident->date_of_birth->format('F d, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Place of Birth</small>
                        <p class="mb-2 fw-semibold">{{ $resident->place_of_birth ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Civil Status</small>
                        <p class="mb-2 fw-semibold">{{ $resident->civil_status }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Nationality</small>
                        <p class="mb-2 fw-semibold">{{ $resident->nationality }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Religion</small>
                        <p class="mb-2 fw-semibold">{{ $resident->religion ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Contact Number</small>
                        <p class="mb-2 fw-semibold">{{ $resident->contact_number ?? 'N/A' }}</p>
                    </div>
                    <div class="col-12">
                        <small class="text-muted">Address</small>
                        <p class="mb-2 fw-semibold">{{ $resident->address }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Occupation</small>
                        <p class="mb-2 fw-semibold">{{ $resident->occupation ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Education</small>
                        <p class="mb-2 fw-semibold">{{ $resident->educational_attainment ?? 'N/A' }}</p>
                    </div>
                    @if($resident->household)
                    <div class="col-12">
                        <small class="text-muted">Household</small>
                        <p class="mb-2 fw-semibold">
                            <a href="{{ route('households.show', $resident->household) }}">{{ $resident->household->household_no }}</a> - {{ $resident->household->address }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @if($resident->certificates->count())
        <div class="card mt-3">
            <div class="card-header py-3"><i class="bi bi-file-earmark-text me-1"></i> Certificates Issued</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Type</th><th>Purpose</th><th>Date</th></tr></thead>
                    <tbody>
                        @foreach($resident->certificates as $cert)
                        <tr>
                            <td>{{ $cert->type }}</td>
                            <td>{{ $cert->purpose }}</td>
                            <td>{{ $cert->date_issued->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
