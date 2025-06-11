@extends('layouts.admin')

@section('title', 'Create Item')

@section('content')
<a href="{{ url('admin/items') }}" class="btn btn-primary mb-3">Kembali</a>
<h1>Create Item</h1>

<form action="{{ route('admin.items.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="kode_item" class="form-label">Kode Item</label>
        <input type="text" name="kode_item" class="form-control" id="kode_item" required>
    </div>

    <div class="mb-3">
        <label for="nama_item" class="form-label">Nama Item</label>
        <input type="text" name="nama_item" class="form-control" id="nama_item" required>
    </div>

    <div class="mb-3">
        <label for="stok" class="form-label">Jumlah</label>
        <input type="number" name="stok" class="form-control" id="stok" required>
    </div>

    <div class="mb-3">
        <label for="category_id" class="form-label">Kategori</label>
        <select name="category_id" class="form-select" required>
            <option value="">Select Category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="pengelola" class="form-label">Pengelola</label>
        <select name="pengelola"  class="form-select" required>
            <option value="">Select Pengelola</option>
            <option value="dpt">DPT</option>
            <option value="sdm">SDM</option>
            <option value="dala">DALA</option>
            <option value="warek">WAREK II</option>
        </select>
    </div>


    <button type="submit" class="btn btn-primary">Create</button>
</form>
@endsection
