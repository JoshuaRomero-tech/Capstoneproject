@extends('layouts.app')
@section('title', 'Officials')
@section('page-title', 'Barangay Officials')
@section('breadcrumb', 'View and manage elected officials')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Barangay Officials</h5>
    <a href="{{ route('officials.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Add Official</a>
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
