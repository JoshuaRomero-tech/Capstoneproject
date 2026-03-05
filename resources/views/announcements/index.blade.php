@extends('layouts.app')
@section('title', 'Announcements')
@section('page-title', 'Announcements')
@section('breadcrumb', 'Manage community announcements')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">All Announcements</h5>
    <a href="{{ route('announcements.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> New Announcement
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Publish Date</th>
                        <th>Expiry</th>
                        <th>Posted By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $announcement)
                    <tr>
                        <td>{{ $loop->iteration + ($announcements->currentPage() - 1) * $announcements->perPage() }}</td>
                        <td class="fw-semibold">
                            <a href="{{ route('announcements.show', $announcement) }}" class="text-decoration-none">
                                {{ Str::limit($announcement->title, 40) }}
                            </a>
                        </td>
                        <td>
                            @php
                                $priorityColors = ['Normal' => 'primary', 'Important' => 'warning', 'Urgent' => 'danger'];
                            @endphp
                            <span class="badge bg-{{ $priorityColors[$announcement->priority] ?? 'secondary' }}">
                                {{ $announcement->priority }}
                            </span>
                        </td>
                        <td>
                            @if($announcement->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $announcement->publish_date->format('M d, Y') }}</td>
                        <td>{{ $announcement->expiry_date ? $announcement->expiry_date->format('M d, Y') : 'No expiry' }}</td>
                        <td class="small text-muted">{{ $announcement->postedBy->name }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('announcements.show', $announcement) }}" class="btn btn-outline-info" title="View"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Delete this announcement?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No announcements yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $announcements->links() }}</div>
@endsection
