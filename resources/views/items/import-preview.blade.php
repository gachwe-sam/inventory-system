@extends('layouts.adminlte')
@section('title', 'Preview Item Import')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title mb-0">Preview: what will happen</h3></div>
    <div class="card-body">
        <p class="text-muted">Nothing has been saved yet. Check the list below, then confirm to actually import.</p>

        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Row</th>
                    <th>Name</th>
                    <th>Result</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($preview as $entry)
                    <tr class="{{ $entry['status'] === 'problem' ? 'table-danger' : 'table-success' }}">
                        <td>{{ $entry['row'] }}</td>
                        <td>{{ $entry['name'] }}</td>
                        <td>{{ $entry['status'] === 'problem' ? $entry['reason'] : ucfirst($entry['status']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <form method="POST" action="{{ route('items.import.confirm') }}" class="d-inline">
            @csrf
            <input type="hidden" name="file_path" value="{{ $filePath }}">
            <button type="submit" class="btn btn-success">
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                <i class="bi bi-check2"></i> Confirm Import
            </button>
        </form>

        <a href="{{ route('items.create') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancel, choose a different file
        </a>
    </div>
</div>
@endsection
