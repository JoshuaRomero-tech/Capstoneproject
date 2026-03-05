@extends('layouts.app')
@section('title', 'Households')
@section('page-title', 'Household Management')
@section('breadcrumb', 'Manage all household records')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">All Households</h5>
    <a href="{{ route('households.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Household
    </a>
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
