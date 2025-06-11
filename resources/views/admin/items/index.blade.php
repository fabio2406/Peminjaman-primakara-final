@extends('layouts.admin')

@section('title', 'Item Management')

@section('content')
<h1>Items</h1>

<div class="mb-3">
    <a href="{{ route('admin.items.create') }}" class="btn btn-primary">Create New Item</a>
    <a href="{{ route('admin.items.import.view') }}" class="btn btn-success">Import Items</a>
</div>
<!-- Form Pencarian dan Filter -->
<div class="mb-3">
    <input type="text" id="search" class="form-control" placeholder="Search by Kode Item or Nama Item" value="{{ request('search') }}">
</div>

<!-- Dropdown filter kategori -->
<div class="d-flex mb-3">
    <select id="category" class="form-select flex-fill me-2">
        <option value="all">All Categories</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
    <select id="pengelola" class="form-select flex-fill ms-2">
        <option value="all">All pengelola</option>
            <option value="dpt">DPT</option>
            <option value="sdm">SDM</option>
            <option value="dala">DALA</option>
            <option value="warek">WAREK II</option>
    </select>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered" id="items-table">
        <thead>
            <tr>
                <th>Kode Item</th>
                <th>Nama Item</th>
                <th class="col-1">Jumlah</th>
                <th>Kategori</th>
                <th>Pengelola</th>
                <th class="col-2">Actions</th>
            </tr>
        </thead>
        <tbody id="items-body">
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->kode_item }}</td>
                    <td>{{ $item->nama_item }}</td>
                    <td>{{ $item->stok }}</td>
                    <td>{{ $item->category->name }}</td>
                    <td>{{ strtoupper($item->pengelola) }}</td>
                    <td class="d-flex justify-content-center">
                        <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-warning mx-auto">Edit</a>
                        <button type="button" class="btn btn-danger mx-1" onclick="openDeleteModal('{{ $item->id }}')">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Pagination Container -->
<div class="pagination-container ">
    {!! $items->links('pagination::bootstrap-5') !!} <!-- Menampilkan pagination Bootstrap 5 -->
</div>

<!-- Modal untuk konfirmasi hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus items ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Iya</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Include jQuery -->
@endsection
@section('scripts')

<script>
$(document).ready(function() {
    $('#search').on('keyup', function() {
        filterItems();
    });

    $('#category').on('change', function() {
        filterItems();
    });

    $('#pengelola').on('change', function() {
        filterItems();
    });

    // Menyimpan nilai pencarian dan kategori
    function filterItems(url = "{{ route('admin.items.index') }}") {
        let searchValue = $('#search').val();
        let categoryValue = $('#category').val();
        let pengelolaValue = $('#pengelola').val();

        $.ajax({
            url: url,
            method: "GET",
            data: { search: searchValue, category: categoryValue, pengelola: pengelolaValue },
            success: function(data) {
                let rows = '';
                data.items.data.forEach(item => {
                    rows += `
                        <tr>
                            <td>${item.kode_item}</td>
                            <td>${item.nama_item}</td>
                            <td>${item.stok}</td>
                            <td>${item.category.name}</td>
                            <td>${item.pengelola.toUpperCase()}</td>
                            <td class="d-flex justify-content-center">
                                <a href="/admin/items/${item.id}/edit" class="btn btn-warning mx-auto">Edit</a>
                               <button type="button" class="btn btn-danger mx-1" onclick="openDeleteModal(${item.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
                $('#items-body').html(rows);

                // Update pagination
                $('.pagination-container').html(data.pagination);
            },
            error: function(xhr, status, error) {
                console.error(xhr);
            }
        });
    }

    // Menghandle link pagination
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        filterItems(url);
    });
});

function openDeleteModal(itemId) {
    $('#deleteForm').attr('action', `/admin/items/${itemId}`);  // Ubah URL form sesuai dengan id peminjaman
    $('#deleteModal').modal('show');  // Tampilkan modal konfirmasi hapus
}
</script>
@endsection
