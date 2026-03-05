@extends('layouts.app')
@section('title', 'Certificates')
@section('page-title', 'Certificate Management')
@section('breadcrumb', 'Issue and track certificates')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Issued Certificates</h5>
    <a href="{{ route('certificates.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Issue Certificate</a>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('certificates.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
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
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i> Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('certificates.index') }}" class="btn btn-sm btn-outline-secondary w-100">Clear</a>
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
                        <th>Resident</th>
                        <th>Type</th>
                        <th>Purpose</th>
                        <th>Amount</th>
                        <th>Date Issued</th>
                        <th>Issued By</th>
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
                        <td>₱{{ number_format($cert->amount, 2) }}</td>
                        <td>{{ $cert->date_issued->format('M d, Y') }}</td>
                        <td class="small text-muted">{{ $cert->issuedBy->name }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('certificates.show', $cert) }}" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('certificates.print', $cert) }}" class="btn btn-outline-success" target="_blank"><i class="bi bi-printer"></i></a>
                                <form action="{{ route('certificates.destroy', $cert) }}" method="POST" onsubmit="return confirm('Delete this certificate?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No certificates found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $certificates->withQueryString()->links() }}</div>
@endsection
