@extends('layouts.app')
@section('title', 'New Blotter')
@section('page-title', 'New Blotter Record')
@section('breadcrumb', 'Blotters > New Record')

@section('content')
<div class="card">
    <div class="card-header py-3"><i class="bi bi-journal-plus me-1"></i> File Blotter Record</div>
    <div class="card-body">
        <form action="{{ route('blotters.store') }}" method="POST">
            @csrf
            @include('blotters._form')
            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('blotters.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Record</button>
            </div>
        </form>
    </div>
</div>
@endsection
