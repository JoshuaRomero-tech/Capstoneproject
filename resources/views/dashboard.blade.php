@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Total Residents</div>
                    <div class="stat-value text-primary">{{ number_format($totalResidents) }}</div>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Households</div>
                    <div class="stat-value text-success">{{ number_format($totalHouseholds) }}</div>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-house-door-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Active Officials</div>
                    <div class="stat-value text-info">{{ number_format($totalOfficials) }}</div>
                </div>
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Pending Blotters</div>
                    <div class="stat-value text-warning">{{ number_format($pendingBlotters) }}</div>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Male</div>
                    <div class="stat-value" style="color: #1976d2;">{{ number_format($totalMale) }}</div>
                </div>
                <div class="stat-icon" style="background: rgba(25,118,210,0.1); color: #1976d2;">
                    <i class="bi bi-gender-male"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Female</div>
                    <div class="stat-value" style="color: #e91e63;">{{ number_format($totalFemale) }}</div>
                </div>
                <div class="stat-icon" style="background: rgba(233,30,99,0.1); color: #e91e63;">
                    <i class="bi bi-gender-female"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Senior Citizens</div>
                    <div class="stat-value text-secondary">{{ number_format($totalSeniorCitizens) }}</div>
                </div>
                <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                    <i class="bi bi-person-wheelchair"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Registered Voters</div>
                    <div class="stat-value" style="color: #ff6f00;">{{ number_format($totalVoters) }}</div>
                </div>
                <div class="stat-icon" style="background: rgba(255,111,0,0.1); color: #ff6f00;">
                    <i class="bi bi-check2-square"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <span><i class="bi bi-people me-1"></i> Recently Added Residents</span>
                <a href="{{ route('residents.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentResidents as $resident)
                            <tr>
                                <td class="fw-semibold">{{ $resident->full_name }}</td>
                                <td>{{ $resident->age }}</td>
                                <td>
                                    <span class="badge {{ $resident->gender == 'Male' ? 'bg-primary' : 'bg-danger' }} bg-opacity-10 {{ $resident->gender == 'Male' ? 'text-primary' : 'text-danger' }}">
                                        {{ $resident->gender }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ Str::limit($resident->address, 30) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No residents yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <span><i class="bi bi-journal-text me-1"></i> Recent Blotter Records</span>
                <a href="{{ route('blotters.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Case No.</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBlotters as $blotter)
                            <tr>
                                <td class="fw-semibold">{{ $blotter->case_no }}</td>
                                <td>
                                    @php
                                        $statusColors = ['Pending' => 'warning', 'Ongoing' => 'info', 'Resolved' => 'success', 'Dismissed' => 'secondary'];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$blotter->status] ?? 'secondary' }}">{{ $blotter->status }}</span>
                                </td>
                                <td class="text-muted small">{{ $blotter->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">No blotter records yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
