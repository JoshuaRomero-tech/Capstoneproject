@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Overview of your barangay')

@section('content')
<style>
    .stat-card.stat-indigo .stat-icon { background: rgba(79,70,229,0.1); color: #4f46e5; }
    .stat-card.stat-indigo .stat-value { color: #4f46e5; }
    .stat-card.stat-emerald .stat-icon { background: rgba(16,185,129,0.1); color: #10b981; }
    .stat-card.stat-emerald .stat-value { color: #10b981; }
    .stat-card.stat-sky .stat-icon { background: rgba(14,165,233,0.1); color: #0ea5e9; }
    .stat-card.stat-sky .stat-value { color: #0ea5e9; }
    .stat-card.stat-amber .stat-icon { background: rgba(245,158,11,0.1); color: #f59e0b; }
    .stat-card.stat-amber .stat-value { color: #f59e0b; }
    .stat-card.stat-blue .stat-icon { background: rgba(59,130,246,0.1); color: #3b82f6; }
    .stat-card.stat-blue .stat-value { color: #3b82f6; }
    .stat-card.stat-rose .stat-icon { background: rgba(244,63,94,0.1); color: #f43f5e; }
    .stat-card.stat-rose .stat-value { color: #f43f5e; }
    .stat-card.stat-slate .stat-icon { background: rgba(100,116,139,0.1); color: #64748b; }
    .stat-card.stat-slate .stat-value { color: #64748b; }
    .stat-card.stat-orange .stat-icon { background: rgba(249,115,22,0.1); color: #f97316; }
    .stat-card.stat-orange .stat-value { color: #f97316; }
    .welcome-banner {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border-radius: 16px; padding: 1.75rem 2rem; color: #fff; margin-bottom: 1.5rem;
        position: relative; overflow: hidden;
    }
    .welcome-banner::before {
        content: ''; position: absolute; right: -40px; top: -40px;
        width: 200px; height: 200px; border-radius: 50%;
        background: rgba(255,255,255,0.08);
    }
    .welcome-banner::after {
        content: ''; position: absolute; right: 60px; bottom: -60px;
        width: 150px; height: 150px; border-radius: 50%;
        background: rgba(255,255,255,0.05);
    }
    .welcome-banner h4 { font-weight: 700; margin-bottom: 0.25rem; position: relative; }
    .welcome-banner p { opacity: 0.8; margin-bottom: 0; font-size: 0.9rem; position: relative; }
</style>

<div class="welcome-banner">
    <h4><i class="bi bi-hand-wave me-2" style="display:inline-block; transform: rotate(-15deg);"></i>Welcome back, {{ auth()->user()->name }}!</h4>
    <p>Here's what's happening in your barangay today.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-indigo">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Total Residents</div>
                    <div class="stat-value">{{ number_format($totalResidents) }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-emerald">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Households</div>
                    <div class="stat-value">{{ number_format($totalHouseholds) }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-house-door-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-sky">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Active Officials</div>
                    <div class="stat-value">{{ number_format($totalOfficials) }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-amber">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Pending Blotters</div>
                    <div class="stat-value">{{ number_format($pendingBlotters) }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-orange">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Pending Certificates</div>
                    <div class="stat-value">{{ number_format($pendingCertificates) }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-blue">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Male</div>
                    <div class="stat-value">{{ number_format($totalMale) }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-gender-male"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-rose">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Female</div>
                    <div class="stat-value">{{ number_format($totalFemale) }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-gender-female"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-slate">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Senior Citizens</div>
                    <div class="stat-value">{{ number_format($totalSeniorCitizens) }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-person-wheelchair"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-orange">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Registered Voters</div>
                    <div class="stat-value">{{ number_format($totalVoters) }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-check2-square"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people me-2"></i>Recently Added Residents</span>
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
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-journal-text me-2"></i>Recent Blotter Reports</span>
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
