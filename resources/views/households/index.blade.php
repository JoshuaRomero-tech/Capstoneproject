@extends('layouts.app')
@section('title', 'Households')
@section('page-title', 'Household Management')
@section('breadcrumb', 'Manage all household records')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">All Households</h5>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-arrow-up me-1"></i> Import
        </button>
        <a href="{{ route('households.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Household
        </a>
    </div>
</div>

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

<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('households.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel"><i class="bi bi-file-earmark-arrow-up me-2"></i>Import Households</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Upload an Excel (.xlsx, .xls) or CSV file. The first row must contain column headers.</p>
                    <div class="mb-3">
                        <label for="importFile" class="form-label fw-semibold">Select File</label>
                        <input type="file" class="form-control" id="importFile" name="file" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">Max file size: 5MB</div>
                    </div>
                    <div class="card bg-light border-0">
                        <div class="card-body py-2 px-3">
                            <p class="fw-semibold mb-1 small">Required columns:</p>
                            <code class="small">household_no, address</code>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('households.template') }}" class="btn btn-outline-secondary me-auto">
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
        <form method="GET" action="{{ route('households.index') }}" class="row g-2 align-items-end">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by household no. or address..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i> Search</button>
            </div>
            <div class="col-md-3">
                <a href="{{ route('households.index') }}" class="btn btn-sm btn-outline-secondary w-100">Clear</a>
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
                        <th>Household No.</th>
                        <th>Address</th>
                        <th>Members</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($households as $household)
                    <tr>
                        <td>{{ $loop->iteration + ($households->currentPage() - 1) * $households->perPage() }}</td>
                        <td class="fw-semibold">
                            <a href="{{ route('households.show', $household) }}" class="text-decoration-none">{{ $household->household_no }}</a>
                        </td>
                        <td>{{ $household->address }}</td>
                        <td><span class="badge bg-primary">{{ $household->residents_count }}</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('households.show', $household) }}" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('households.edit', $household) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('households.destroy', $household) }}" method="POST" onsubmit="return confirm('Delete this household?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No households found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $households->withQueryString()->links() }}</div>
@endsection
