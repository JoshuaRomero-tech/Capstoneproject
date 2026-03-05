@extends('layouts.app')
@section('title', 'Edit Blotter')
@section('page-title', 'Edit Blotter Record')
@section('breadcrumb', 'Blotters > Edit')

@section('content')
<div class="card">
    <div class="card-header py-3"><i class="bi bi-pencil-square me-1"></i> Edit Blotter: {{ $blotter->case_no }}</div>
    <div class="card-body">
        <form action="{{ route('blotters.update', $blotter) }}" method="POST">
            @csrf @method('PUT')
            @include('blotters._form', ['editing' => true])
            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('blotters.show', $blotter) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Update Record</button>
            </div>
        </form>
    </div>
</div>
@endsection
