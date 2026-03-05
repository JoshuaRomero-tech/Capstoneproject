@extends('layouts.app')
@section('title', 'Certificate Requests')
@section('page-title', 'Certificate Requests')
@section('breadcrumb', 'Review and manage certificate requests')

@section('content')
{{-- Status Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <a href="{{ route('certificates.index', ['status' => 'Pending']) }}" class="text-decoration-none">
            <div class="card border-start border-4 border-warning shadow-sm">
                <div class="card-body d-flex align-items-center py-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                        <i class="bi bi-hourglass-split text-warning fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Pending Review</div>
                        <div class="fw-bold fs-4 text-warning">{{ $pendingCount }}</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('certificates.index', ['status' => 'Approved']) }}" class="text-decoration-none">
            <div class="card border-start border-4 border-success shadow-sm">
                <div class="card-body d-flex align-items-center py-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="bi bi-check-circle text-success fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Approved</div>
                        <div class="fw-bold fs-4 text-success">{{ $approvedCount }}</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('certificates.index', ['status' => 'Disapproved']) }}" class="text-decoration-none">
            <div class="card border-start border-4 border-danger shadow-sm">
                <div class="card-body d-flex align-items-center py-3">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                        <i class="bi bi-x-circle text-danger fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Disapproved</div>
                        <div class="fw-bold fs-4 text-danger">{{ $disapprovedCount }}</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('certificates.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by resident name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    @foreach(['Barangay Clearance','Certificate of Residency','Certificate of Indigency','Business Clearance','Barangay ID'] as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    @foreach(['Pending','Approved','Disapproved'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i> Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('certificates.index') }}" class="btn btn-sm btn-outline-secondary w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Resident</th>
                        <th>Type</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>Requested</th>
                        <th>Reviewed By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificates as $cert)
                    <tr>
                        <td>{{ $loop->iteration + ($certificates->currentPage() - 1) * $certificates->perPage() }}</td>
                        <td class="fw-semibold">{{ $cert->resident->full_name }}</td>
                        <td><span class="badge bg-info bg-opacity-10 text-info">{{ $cert->type }}</span></td>
                        <td>{{ Str::limit($cert->purpose, 25) }}</td>
                        <td>
                            @php
                                $statusColors = ['Pending' => 'warning', 'Approved' => 'success', 'Disapproved' => 'danger'];
                                $statusIcons = ['Pending' => 'hourglass-split', 'Approved' => 'check-circle-fill', 'Disapproved' => 'x-circle-fill'];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$cert->status] ?? 'secondary' }}">
                                <i class="bi bi-{{ $statusIcons[$cert->status] ?? 'question' }} me-1"></i>{{ $cert->status }}
                            </span>
                        </td>
                        <td class="small text-muted">{{ $cert->created_at->format('M d, Y') }}</td>
                        <td class="small text-muted">{{ $cert->reviewedBy ? $cert->reviewedBy->name : '—' }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('certificates.show', $cert) }}" class="btn btn-outline-info" title="View"><i class="bi bi-eye"></i></a>
                                @if($cert->status === 'Approved')
                                    <a href="{{ route('certificates.print', $cert) }}" class="btn btn-outline-success" target="_blank" title="Print"><i class="bi bi-printer"></i></a>
                                @endif
                                <form action="{{ route('certificates.destroy', $cert) }}" method="POST" onsubmit="return confirm('Delete this request?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No certificate requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $certificates->withQueryString()->links() }}</div>
@endsection
