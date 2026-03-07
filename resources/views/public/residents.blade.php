@extends('layouts.public')
@section('title', 'Resident Directory - CiviTrack')

@section('content')
<div class="page-banner">
    <div class="container">
        <h2><i class="bi bi-people-fill me-2"></i>Resident Directory</h2>
        <p>Search and browse registered residents in the barangay</p>
    </div>
</div>

<div class="container py-4">
    {{-- Search & Filter --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('public.residents') }}" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Search by Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Enter name..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="">All Gender</option>
                        <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search me-1"></i> Search</button>
                    <a href="{{ route('public.residents') }}" class="btn btn-outline-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Results --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-list-ul me-2"></i>Residents ({{ $residents->total() }} total)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Civil Status</th>
                            <th>Address</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($residents as $resident)
                        <tr>
                            <td>{{ $loop->iteration + ($residents->currentPage() - 1) * $residents->perPage() }}</td>
                            <td class="fw-semibold">{{ $resident->full_name }}</td>
                            <td>{{ $resident->age }}</td>
                            <td>
                                <span class="badge {{ $resident->gender == 'Male' ? 'text-primary' : 'text-danger' }}" style="background: {{ $resident->gender == 'Male' ? 'rgba(79,70,229,0.1)' : 'rgba(244,63,94,0.1)' }};">
                                    <i class="bi bi-gender-{{ strtolower($resident->gender) }} me-1"></i>{{ $resident->gender }}
                                </span>
                            </td>
                            <td>{{ $resident->civil_status }}</td>
                            <td class="text-muted small">{{ Str::limit($resident->address, 30) }}</td>
                            <td>
                                <a href="{{ route('public.residents.show', $resident) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-search fs-1 d-block mb-2 opacity-25"></i>
                                No residents found. Try adjusting your search.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">{{ $residents->withQueryString()->links() }}</div>
</div>
@endsection
