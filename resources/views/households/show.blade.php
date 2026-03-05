@extends('layouts.app')
@section('title', 'Household ' . $household->household_no)
@section('page-title', 'Household Details')
@section('breadcrumb', 'Households > Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('households.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
    <a href="{{ route('households.edit', $household) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <small class="text-muted">Household No.</small>
                <p class="fw-bold fs-5">{{ $household->household_no }}</p>
            </div>
            <div class="col-md-4">
                <small class="text-muted">Address</small>
                <p class="fw-semibold">{{ $household->address }}</p>
            </div>
            <div class="col-md-4">
                <small class="text-muted">Total Members</small>
                <p class="fw-bold fs-5 text-primary">{{ $household->residents->count() }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header py-3"><i class="bi bi-people me-1"></i> Household Members</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Name</th><th>Age</th><th>Gender</th><th>Relation</th><th>Status</th></tr>
            </thead>
            <tbody>
                @forelse($household->residents as $resident)
                <tr>
                    <td><a href="{{ route('residents.show', $resident) }}" class="text-decoration-none fw-semibold">{{ $resident->full_name }}</a></td>
                    <td>{{ $resident->age }}</td>
                    <td>{{ $resident->gender }}</td>
                    <td>{{ $resident->civil_status }}</td>
                    <td><span class="badge bg-{{ $resident->status == 'Active' ? 'success' : 'secondary' }}">{{ $resident->status }}</span></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No members in this household.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
