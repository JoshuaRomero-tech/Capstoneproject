@extends('layouts.public')
@section('title', 'Welcome - CiviTrack')

@section('content')
{{-- Hero Section --}}
<section class="hero-section">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1>Welcome to Our<br>Barangay Community</h1>
                <p class="mb-4">Your one-stop portal for barangay services, resident information, and community updates. Access public records and request certificates online.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('public.services') }}" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-file-earmark-text me-2"></i>Request a Certificate
                    </a>
                    <a href="{{ route('public.residents') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-search me-2"></i>Find a Resident
                    </a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center">
                <div style="font-size: 10rem; opacity: 0.08; line-height: 1;">
                    <i class="bi bi-building"></i>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Quick Stats --}}
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div style="width: 64px; height: 64px; border-radius: 16px; background: rgba(79,70,229,0.1); color: #4f46e5; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 1rem;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h2 class="fw-800 mb-0" style="color: #4f46e5;">{{ number_format($totalResidents) }}</h2>
                    <p class="text-muted mb-0">Active Residents</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div style="width: 64px; height: 64px; border-radius: 16px; background: rgba(16,185,129,0.1); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 1rem;">
                        <i class="bi bi-house-door-fill"></i>
                    </div>
                    <h2 class="fw-800 mb-0" style="color: #10b981;">{{ number_format($totalHouseholds) }}</h2>
                    <p class="text-muted mb-0">Households</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div style="width: 64px; height: 64px; border-radius: 16px; background: rgba(14,165,233,0.1); color: #0ea5e9; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 1rem;">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <h2 class="fw-800 mb-0" style="color: #0ea5e9;">{{ number_format($totalOfficials) }}</h2>
                    <p class="text-muted mb-0">Active Officials</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Announcements --}}
@if($announcements->count() > 0)
<section class="py-5" style="background: #fff;">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-800 mb-2"><i class="bi bi-megaphone me-2"></i>Announcements</h2>
            <p class="text-muted">Stay updated with the latest news and notices from the barangay</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @foreach($announcements as $announcement)
                <div class="card mb-3" style="border-left: 4px solid {{ $announcement->priority == 'Urgent' ? '#ef4444' : ($announcement->priority == 'Important' ? '#f59e0b' : '#4f46e5') }};">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    @if($announcement->priority == 'Urgent')
                                        <span class="badge bg-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>Urgent</span>
                                    @elseif($announcement->priority == 'Important')
                                        <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-circle-fill me-1"></i>Important</span>
                                    @endif
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>{{ $announcement->publish_date->format('M d, Y') }}
                                    </small>
                                </div>
                                <h5 class="fw-700 mb-2" style="color: #0f172a;">{{ $announcement->title }}</h5>
                                <p class="text-muted mb-0" style="line-height: 1.7;">{{ Str::limit($announcement->content, 200) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

{{-- Services Overview --}}
<section class="py-5" style="background: {{ $announcements->count() > 0 ? '#f8fafc' : '#fff' }};">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-800 mb-2">Barangay Services</h2>
            <p class="text-muted">Access our services online — no need to visit the barangay hall for initial requests</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon" style="background: rgba(79,70,229,0.1); color: #4f46e5;">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <h5>Certificate Requests</h5>
                    <p>Request barangay clearance, residency certificates, indigency certificates, and more.</p>
                    <a href="{{ route('public.certificate-request') }}" class="btn btn-sm btn-primary">Request Now</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon" style="background: rgba(245,158,11,0.1); color: #f59e0b;">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <h5>File a Blotter</h5>
                    <p>Report incidents and file complaints for proper documentation and mediation.</p>
                    <a href="{{ route('public.blotter-file') }}" class="btn btn-sm btn-outline-primary">File Report</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon" style="background: rgba(16,185,129,0.1); color: #10b981;">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5>Resident Lookup</h5>
                    <p>Search and view registered residents in the barangay directory.</p>
                    <a href="{{ route('public.residents') }}" class="btn btn-sm btn-outline-primary">Search Directory</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Officials --}}
@if($officials->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-800 mb-2">Barangay Officials</h2>
            <p class="text-muted">Meet our elected officials serving the community</p>
        </div>
        <div class="row g-4 justify-content-center">
            @foreach($officials as $official)
            <div class="col-lg-3 col-md-6">
                <div class="official-card">
                    <div class="avatar">
                        {{ strtoupper(substr($official->resident->first_name, 0, 1)) }}{{ strtoupper(substr($official->resident->last_name, 0, 1)) }}
                    </div>
                    <div class="name">{{ $official->resident->full_name }}</div>
                    <div class="position">{{ $official->position }}</div>
                    @if($official->committee)
                        <div class="committee mt-1">{{ $official->committee }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('public.officials') }}" class="btn btn-outline-primary">View All Officials</a>
        </div>
    </div>
</section>
@endif
@endsection
