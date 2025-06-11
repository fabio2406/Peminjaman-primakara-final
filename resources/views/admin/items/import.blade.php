@extends('layouts.admin')

@section('title', 'Import Items')

@section('content')
<a href="{{ url('admin/items') }}" class="btn btn-primary">Kembali</a>

<div class="container mt-5">
    <h2>Import Items dari Excel</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ asset('templates/template_items.xlsx') }}" class="btn btn-success mb-3">
        Unduh Template Excel
    </a>

    <form action="{{ route('admin.items.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label">Pilih file Excel (.xls, .xlsx, .csv)</label>
            <input type="file" class="form-control" name="file" id="file" required>
        </div>
        @error('file')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <button type="submit" class="btn btn-primary">Import</button>
    </form>
</div>


@endsection