@extends('layouts.app')
@section('title', 'Blotter Reports')
@section('page-title', 'Blotter Reports')
@section('breadcrumb', 'Review and manage blotter reports')

@section('content')
{{-- Status Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <a href="{{ route('blotters.index', ['status' => 'Pending']) }}" class="text-decoration-none">
            <div class="card border-start border-4 border-warning shadow-sm">
                <div class="card-body d-flex align-items-center py-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                        <i class="bi bi-hourglass-split text-warning fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Pending</div>
                        <div class="fw-bold fs-4 text-warning">{{ $pendingCount }}</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('blotters.index', ['status' => 'Ongoing']) }}" class="text-decoration-none">
            <div class="card border-start border-4 border-info shadow-sm">
                <div class="card-body d-flex align-items-center py-3">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                        <i class="bi bi-arrow-repeat text-info fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Ongoing</div>
                        <div class="fw-bold fs-4 text-info">{{ $ongoingCount }}</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('blotters.index', ['status' => 'Resolved']) }}" class="text-decoration-none">
            <div class="card border-start border-4 border-success shadow-sm">
                <div class="card-body d-flex align-items-center py-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="bi bi-check-circle text-success fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Resolved</div>
                        <div class="fw-bold fs-4 text-success">{{ $resolvedCount }}</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('blotters.index', ['status' => 'Dismissed']) }}" class="text-decoration-none">
            <div class="card border-start border-4 border-secondary shadow-sm">
                <div class="card-body d-flex align-items-center py-3">
                    <div class="rounded-circle bg-secondary bg-opacity-10 p-3 me-3">
                        <i class="bi bi-x-circle text-secondary fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Dismissed</div>
                        <div class="fw-bold fs-4 text-secondary">{{ $dismissedCount }}</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('blotters.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search case no. or complainant..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    @foreach(['Pending','Ongoing','Resolved','Dismissed'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i> Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('blotters.index') }}" class="btn btn-sm btn-outline-secondary w-100">Clear</a>
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
                        <th>Case No.</th>
                        <th>Complainant</th>
                        <th>Respondent</th>
                        <th>Incident Type</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blotters as $blotter)
                    <tr>
                        <td class="fw-semibold">{{ $blotter->case_no }}</td>
                        <td>{{ $blotter->complainant->full_name }}</td>
                        <td>{{ $blotter->respondent->full_name }}</td>
                        <td>{{ $blotter->incident_type }}</td>
                        <td>{{ $blotter->incident_date->format('M d, Y') }}</td>
                        <td>
                            @php $statusColors = ['Pending' => 'warning', 'Ongoing' => 'info', 'Resolved' => 'success', 'Dismissed' => 'secondary']; @endphp
                            <span class="badge bg-{{ $statusColors[$blotter->status] ?? 'secondary' }}">{{ $blotter->status }}</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('blotters.show', $blotter) }}" class="btn btn-outline-info" title="View"><i class="bi bi-eye"></i></a>
                                @if($blotter->status === 'Pending' || $blotter->status === 'Ongoing')
                                    <a href="{{ route('blotters.review', $blotter) }}" class="btn btn-outline-primary" title="Review"><i class="bi bi-clipboard-check"></i></a>
                                @endif
                                <form action="{{ route('blotters.destroy', $blotter) }}" method="POST" onsubmit="return confirm('Delete this record?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No blotter reports found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $blotters->withQueryString()->links() }}</div>
@endsection
