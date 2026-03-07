@extends('layouts.app')
@section('title', 'Residents')
@section('page-title', 'Resident Management')
@section('breadcrumb', 'Manage all barangay residents')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0">All Residents</h5>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-arrow-up me-1"></i> Import
        </button>
        <a href="{{ route('residents.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Resident
        </a>
    </div>
</div>

{{-- Import success/error messages --}}
@if(session('warning'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    {{ session('warning') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('import_errors'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Import issues:</strong>
    <ul class="mb-0 mt-1">
        @foreach(session('import_errors') as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('residents.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel"><i class="bi bi-file-earmark-arrow-up me-2"></i>Import Residents</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Upload an Excel (.xlsx, .xls) or CSV file with resident data. The first row must contain column headers.</p>
                    <div class="mb-3">
                        <label for="importFile" class="form-label fw-semibold">Select File</label>
                        <input type="file" class="form-control" id="importFile" name="file" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">Max file size: 5MB</div>
                    </div>
                    <div class="card bg-light border-0">
                        <div class="card-body py-2 px-3">
                            <p class="fw-semibold mb-1 small">Required columns:</p>
                            <code class="small">first_name, last_name, date_of_birth, gender, address</code>
                            <p class="fw-semibold mb-1 mt-2 small">Optional columns:</p>
                            <code class="small">middle_name, suffix, place_of_birth, civil_status, nationality, religion, contact_number, email, occupation, educational_attainment, voter_status</code>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('residents.template') }}" class="btn btn-outline-secondary me-auto">
                        <i class="bi bi-download me-1"></i> Download Template
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-upload me-1"></i> Import</button>
                </div>
            </form>
        </div>
    </div>
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
