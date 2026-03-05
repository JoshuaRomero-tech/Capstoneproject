@extends('layouts.app')
@section('title', 'Residents')
@section('page-title', 'Resident Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0">All Residents</h5>
    </div>
    <a href="{{ route('residents.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Resident
    </a>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('residents.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="gender" class="form-select form-select-sm">
                    <option value="">All Gender</option>
                    <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="Deceased" {{ request('status') == 'Deceased' ? 'selected' : '' }}>Deceased</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i> Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('residents.index') }}" class="btn btn-sm btn-outline-secondary w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
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
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($residents as $resident)
                    <tr>
                        <td>{{ $loop->iteration + ($residents->currentPage() - 1) * $residents->perPage() }}</td>
                        <td class="fw-semibold">
                            <a href="{{ route('residents.show', $resident) }}" class="text-decoration-none">
                                {{ $resident->full_name }}
                            </a>
                        </td>
                        <td>{{ $resident->age }}</td>
                        <td>
                            <span class="badge {{ $resident->gender == 'Male' ? 'bg-primary' : 'bg-danger' }} bg-opacity-10 {{ $resident->gender == 'Male' ? 'text-primary' : 'text-danger' }}">
                                {{ $resident->gender }}
                            </span>
                        </td>
                        <td>{{ $resident->civil_status }}</td>
                        <td class="small text-muted">{{ Str::limit($resident->address, 25) }}</td>
                        <td>
                            @php
                                $statusColors = ['Active' => 'success', 'Inactive' => 'warning', 'Deceased' => 'secondary'];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$resident->status] ?? 'secondary' }}">{{ $resident->status }}</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('residents.show', $resident) }}" class="btn btn-outline-info" title="View"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('residents.edit', $resident) }}" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('residents.destroy', $resident) }}" method="POST" onsubmit="return confirm('Delete this resident?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No residents found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $residents->withQueryString()->links() }}</div>
@endsection
