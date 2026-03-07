@extends('layouts.app')
@section('title', 'Officials')
@section('page-title', 'Barangay Officials')
@section('breadcrumb', 'View and manage elected officials')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Barangay Officials</h5>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-arrow-up me-1"></i> Import
        </button>
        <a href="{{ route('officials.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Add Official</a>
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
            <form action="{{ route('officials.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel"><i class="bi bi-file-earmark-arrow-up me-2"></i>Import Officials</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Upload an Excel (.xlsx, .xls) or CSV file. The first row must contain column headers. Residents must already exist in the system.</p>
                    <div class="mb-3">
                        <label for="importFile" class="form-label fw-semibold">Select File</label>
                        <input type="file" class="form-control" id="importFile" name="file" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">Max file size: 5MB</div>
                    </div>
                    <div class="card bg-light border-0">
                        <div class="card-body py-2 px-3">
                            <p class="fw-semibold mb-1 small">Required columns:</p>
                            <code class="small">name, position, term_start, term_end</code>
                            <p class="fw-semibold mb-1 mt-2 small">Optional columns:</p>
                            <code class="small">committee, status</code>
                            <p class="mt-2 mb-0 small text-muted"><i class="bi bi-info-circle me-1"></i>The "name" column should match an existing resident's name (e.g., "Juan Dela Cruz").</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('officials.template') }}" class="btn btn-outline-secondary me-auto">
                        <i class="bi bi-download me-1"></i> Download Template
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-upload me-1"></i> Import</button>
                </div>
            </form>
        </div>
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
                        <th>Position</th>
                        <th>Committee</th>
                        <th>Term</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($officials as $official)
                    <tr>
                        <td>{{ $loop->iteration + ($officials->currentPage() - 1) * $officials->perPage() }}</td>
                        <td class="fw-semibold">{{ $official->resident->full_name }}</td>
                        <td>{{ $official->position }}</td>
                        <td>{{ $official->committee ?? 'N/A' }}</td>
                        <td class="small">{{ $official->term_start->format('M Y') }} - {{ $official->term_end->format('M Y') }}</td>
                        <td><span class="badge bg-{{ $official->status == 'Active' ? 'success' : 'secondary' }}">{{ $official->status }}</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('officials.edit', $official) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('officials.destroy', $official) }}" method="POST" onsubmit="return confirm('Remove this official?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No officials found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $officials->links() }}</div>
@endsection
