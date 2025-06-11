@extends('layouts.admin')

@section('title', 'Edit Item')

@section('content')
<a href="{{ url('admin/items') }}" class="btn btn-primary mb-3">Kembali</a>
<h1>Edit Item</h1>

<form action="{{ route('admin.items.update', $item->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="kode_item" class="form-label">Kode Item</label>
        <input type="text" name="kode_item" class="form-control" id="kode_item" value="{{ $item->kode_item }}" required>
    </div>

    <div class="mb-3">
        <label for="nama_item" class="form-label">Nama Item</label>
        <input type="text" name="nama_item" class="form-control" id="nama_item" value="{{ $item->nama_item }}" required>
    </div>

    <div class="mb-3">
        <label for="stok" class="form-label">Stok</label>
        <input type="number" name="stok" class="form-control" id="stok" value="{{ $item->stok }}" required>
    </div>

    <div class="mb-3">
        <label for="category_id" class="form-label">Kategori</label>
        <select name="category_id" class="form-select" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $category->id == $item->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="pengelola" class="form-label">Pengelola</label>
        <select name="pengelola"  class="form-select" required>
            <option value="dpt"   {{ $item->pengelola == 'dpt' ? 'selected' : '' }}>DPT</option>
            <option value="sdm"   {{ $item->pengelola == 'sdm' ? 'selected' : '' }}>SDM</option>
            <option value="dala"  {{ $item->pengelola == 'dala' ? 'selected' : '' }}>DALA</option>
            <option value="warek" {{ $item->pengelola == 'warek' ? 'selected' : '' }}>WAREK II</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection
