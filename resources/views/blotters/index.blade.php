@extends('layouts.app')
@section('title', 'Blotter Records')
@section('page-title', 'Blotter Records')
@section('breadcrumb', 'Track and manage blotter cases')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Blotter Records</h5>
    <a href="{{ route('blotters.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> New Blotter</a>
</div>

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
                                <a href="{{ route('blotters.show', $blotter) }}" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('blotters.edit', $blotter) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('blotters.destroy', $blotter) }}" method="POST" onsubmit="return confirm('Delete this record?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No blotter records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $blotters->withQueryString()->links() }}</div>
@endsection
