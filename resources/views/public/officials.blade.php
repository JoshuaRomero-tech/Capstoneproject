@extends('layouts.public')
@section('title', 'Barangay Officials - CiviTrack')

@section('content')
<div class="page-banner">
    <div class="container">
        <h2><i class="bi bi-person-badge-fill me-2"></i>Barangay Officials</h2>
        <p>Meet the elected officials serving our community</p>
    </div>
</div>

<div class="container py-5">
    @if($officials->count() > 0)
        <div class="row g-4">
            @foreach($officials as $official)
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="official-card">
                    <div class="avatar">
                        {{ strtoupper(substr($official->resident->first_name, 0, 1)) }}{{ strtoupper(substr($official->resident->last_name, 0, 1)) }}
                    </div>
                    <div class="name">{{ $official->resident->full_name }}</div>
                    <div class="position">{{ $official->position }}</div>
                    @if($official->committee)
                        <div class="committee mt-1"><i class="bi bi-diagram-3 me-1"></i>{{ $official->committee }}</div>
                    @endif
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $official->term_start->format('M Y') }} - {{ $official->term_end->format('M Y') }}
                        </small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-person-badge fs-1 text-muted opacity-25 d-block mb-3"></i>
            <h5 class="text-muted">No active officials found</h5>
            <p class="text-muted">Official records will appear here once they are added to the system.</p>
        </div>
    @endif
</div>
@endsection
