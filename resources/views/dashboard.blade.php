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

<!-- Time Period Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('dashboard') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">
                    <i class="bi bi-calendar-range me-1"></i>Filter Year
                </label>
                <select name="year" class="form-select">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">
                    <i class="bi bi-calendar-month me-1"></i>Filter Month
                </label>
                <select name="month" class="form-select">
                    <option value="">All Months</option>
                    @php
                        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                    @endphp
                    @foreach($months as $i => $monthName)
                        <option value="{{ $i + 1 }}" {{ $month == ($i + 1) ? 'selected' : '' }}>
                            {{ $monthName }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-filter me-1"></i> Apply Filters
                </button>
            </div>
            @if($year != now()->year || $month)
                <div class="col-md-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                </div>
            @endif
        </form>
    </div>
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

<!-- Demographics Analytics -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-people me-2"></i>Demographics Analytics</span>
        <span class="badge bg-primary">Population Insights</span>
    </div>
    <div class="card-body">
        <!-- Age & Gender Row -->
        <div class="row g-3 mb-3">
            <div class="col-lg-6">
                <h6 class="text-muted small mb-2 fw-semibold">
                    <i class="bi bi-bar-chart-fill me-1"></i>Age Distribution
                </h6>
                <div style="height: 180px;">
                    <canvas id="ageChart"></canvas>
                </div>
            </div>
            <div class="col-lg-6">
                <h6 class="text-muted small mb-2 fw-semibold">
                    <i class="bi bi-pie-chart-fill me-1"></i>Gender Distribution
                </h6>
                <div style="height: 180px;">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>

        <hr class="my-3">

        <!-- Civil Status, Education & Special Categories Row -->
        <div class="row g-3">
            <div class="col-lg-4">
                <h6 class="text-muted small mb-2 fw-semibold">
                    <i class="bi bi-heart-fill me-1"></i>Civil Status
                </h6>
                <div style="height: 160px;">
                    <canvas id="civilStatusChart"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <h6 class="text-muted small mb-2 fw-semibold">
                    <i class="bi bi-mortarboard-fill me-1"></i>Educational Attainment
                </h6>
                <div style="height: 160px;">
                    <canvas id="educationChart"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <h6 class="text-muted small mb-2 fw-semibold">
                    <i class="bi bi-star-fill me-1"></i>Special Categories
                </h6>
                <div style="height: 160px;">
                    <canvas id="specialCategoriesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Blotter Incident Analytics -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-shield-exclamation me-2"></i>Blotter Incident Analytics</span>
        <span class="badge bg-warning">Resolution Rate: {{ $blotterResolutionRate }}%</span>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-lg-7">
                <h6 class="text-muted small mb-2 fw-semibold">
                    <i class="bi bi-graph-up me-1"></i>Monthly Incident Trend ({{ $year }})
                </h6>
                <div style="height: 200px;">
                    <canvas id="blotterTrendChart"></canvas>
                </div>
            </div>
            <div class="col-lg-5">
                <h6 class="text-muted small mb-2 fw-semibold">
                    <i class="bi bi-list-ul me-1"></i>Incident Types
                </h6>
                <div style="height: 200px;">
                    <canvas id="incidentTypesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Analytics -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-currency-dollar me-2"></i>Certificate Revenue Analytics</span>
        <span class="badge bg-success">Total: ₱{{ number_format(array_sum($revenueData), 2) }}</span>
    </div>
    <div class="card-body">
        <h6 class="text-muted small mb-2 fw-semibold">
            <i class="bi bi-graph-up-arrow me-1"></i>Monthly Revenue from Certificates ({{ $year }})
        </h6>
        <div style="height: 200px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>

<!-- Quick Insights & Alerts -->
<div class="card mb-4">
    <div class="card-header">
        <i class="bi bi-lightbulb me-2"></i>Quick Insights & Action Items
    </div>
    <div class="card-body py-2">
        <div class="row g-2">
            @if($insights['pending_certificates_7days'] > 0)
            <div class="col-md-6">
                <div class="alert alert-warning mb-0 py-2 d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div>
                        <strong class="d-block">{{ $insights['pending_certificates_7days'] }}</strong>
                        <small>certificates pending for more than 7 days</small>
                    </div>
                </div>
            </div>
            @endif

            @if($insights['unresolved_blotters_30days'] > 0)
            <div class="col-md-6">
                <div class="alert alert-danger mb-0 py-2 d-flex align-items-center">
                    <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                    <div>
                        <strong class="d-block">{{ $insights['unresolved_blotters_30days'] }}</strong>
                        <small>blotters unresolved for over 30 days</small>
                    </div>
                </div>
            </div>
            @endif

            @if($insights['top_certificate_type'])
            <div class="col-md-6">
                <div class="alert alert-info mb-0 py-2 d-flex align-items-center">
                    <i class="bi bi-graph-up me-2 fs-5"></i>
                    <div>
                        <strong class="d-block">{{ $insights['top_certificate_type']->type }}</strong>
                        <small>Most requested ({{ $insights['top_certificate_type']->count }} requests)</small>
                    </div>
                </div>
            </div>
            @endif

            @if($insights['avg_processing_days'])
            <div class="col-md-6">
                <div class="alert alert-success mb-0 py-2 d-flex align-items-center">
                    <i class="bi bi-clock-history me-2 fs-5"></i>
                    <div>
                        <strong class="d-block">{{ number_format($insights['avg_processing_days'], 1) }} days</strong>
                        <small>Average processing time</small>
                    </div>
                </div>
            </div>
            @endif
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

@push('scripts')
<script>
// Chart.js global configuration
Chart.defaults.font.family = 'Inter, sans-serif';
Chart.defaults.color = '#64748b';
Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(15, 23, 42, 0.9)';
Chart.defaults.plugins.tooltip.padding = 12;
Chart.defaults.plugins.tooltip.borderRadius = 8;

// Color palette matching the dashboard theme
const colors = {
    primary: '#4f46e5',
    secondary: '#7c3aed',
    success: '#10b981',
    danger: '#ef4444',
    warning: '#f59e0b',
    info: '#0ea5e9',
    indigo: '#6366f1',
    purple: '#a855f7',
    pink: '#ec4899',
    blue: '#3b82f6',
    slate: '#64748b',
};

// 1. Age Distribution Chart
const ageCtx = document.getElementById('ageChart');
new Chart(ageCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_keys($ageDistribution)) !!},
        datasets: [{
            label: 'Population',
            data: {!! json_encode(array_values($ageDistribution)) !!},
            backgroundColor: [colors.info, colors.primary, colors.warning],
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { precision: 0 }
            }
        }
    }
});

// 2. Gender Distribution Chart
const genderCtx = document.getElementById('genderChart');
new Chart(genderCtx, {
    type: 'doughnut',
    data: {
        labels: ['Male', 'Female'],
        datasets: [{
            data: [
                {{ $genderDistribution['Male'] ?? 0 }},
                {{ $genderDistribution['Female'] ?? 0 }}
            ],
            backgroundColor: [colors.blue, colors.pink],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { padding: 10, usePointStyle: true }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                        return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                    }
                }
            }
        }
    }
});

// 3. Civil Status Chart
const civilStatusCtx = document.getElementById('civilStatusChart');
new Chart(civilStatusCtx, {
    type: 'pie',
    data: {
        labels: {!! json_encode(array_keys($civilStatusDistribution)) !!},
        datasets: [{
            data: {!! json_encode(array_values($civilStatusDistribution)) !!},
            backgroundColor: [
                colors.primary, colors.success, colors.warning,
                colors.danger, colors.secondary
            ],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { padding: 8, usePointStyle: true, font: { size: 10 } }
            }
        }
    }
});

// 4. Education Chart
const educationCtx = document.getElementById('educationChart');
new Chart(educationCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_keys($educationDistribution)) !!},
        datasets: [{
            label: 'Residents',
            data: {!! json_encode(array_values($educationDistribution)) !!},
            backgroundColor: colors.indigo,
            borderRadius: 6,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: {
                beginAtZero: true,
                ticks: { precision: 0, font: { size: 10 } }
            },
            y: {
                ticks: { font: { size: 10 } }
            }
        }
    }
});

// 5. Special Categories Chart
const specialCategoriesCtx = document.getElementById('specialCategoriesChart');
new Chart(specialCategoriesCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_keys($specialCategoriesDistribution)) !!},
        datasets: [{
            label: 'Count',
            data: {!! json_encode(array_values($specialCategoriesDistribution)) !!},
            backgroundColor: [colors.warning, colors.success, colors.danger, colors.primary],
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { precision: 0, font: { size: 10 } }
            },
            x: {
                ticks: { font: { size: 9 } }
            }
        }
    }
});

// 6. Blotter Trend Chart
const blotterTrendCtx = document.getElementById('blotterTrendChart');
new Chart(blotterTrendCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: {!! json_encode($blotterTrend) !!}
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: {
                position: 'bottom',
                labels: { padding: 10, usePointStyle: true, font: { size: 10 } }
            }
        },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0, font: { size: 10 } } },
            x: { ticks: { font: { size: 10 } } }
        }
    }
});

// 7. Incident Types Chart
const incidentTypesCtx = document.getElementById('incidentTypesChart');
new Chart(incidentTypesCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_keys($incidentTypesDistribution)) !!},
        datasets: [{
            label: 'Incidents',
            data: {!! json_encode(array_values($incidentTypesDistribution)) !!},
            backgroundColor: colors.danger,
            borderRadius: 6,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: {
                beginAtZero: true,
                ticks: { precision: 0, font: { size: 10 } }
            },
            y: {
                ticks: { font: { size: 10 } }
            }
        }
    }
});

// 8. Revenue Chart
const revenueCtx = document.getElementById('revenueChart');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Revenue (₱)',
            data: {!! json_encode($revenueData) !!},
            borderColor: colors.success,
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Revenue: ₱' + context.parsed.y.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    font: { size: 10 },
                    callback: function(value) {
                        return '₱' + value.toLocaleString();
                    }
                }
            },
            x: {
                ticks: { font: { size: 10 } }
            }
        }
    }
});
</script>
@endpush
