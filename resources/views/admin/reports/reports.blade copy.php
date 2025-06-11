@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<form action="{{ route('admin.reports.export') }}" method="GET" id="exportForm">
    <button type="submit" class="btn btn-primary mb-3">Export to Excel</button>

<p>Filter user</p>
<div class="d-flex mb-3">
    <input type="text" id="searchUserName" class="form-control flex-fill mx-1" placeholder="Cari Nama pengguna...">
    <input type="text" id="searchUserUsername" class="form-control flex-fill mx-1" placeholder="Cari Nim pengguna...">
    <input type="text" id="searchUserPhone" class="form-control flex-fill mx-1" placeholder="Cari telepon pengguna...">
    <div class="flex-fill mx-1">
        <select id="roleFilter" class="form-select">
            <option value="all">All Roles</option>
            @foreach($roles as $role)
                <option value="{{ $role }}">{{ ucfirst($role) }}</option>
            @endforeach
        </select>
    </div>
</div>

<p>Filter Item</p>
<div class="d-flex mb-3">
    <input type="text" id="searchItemCode" class="form-control flex-fill mx-1" placeholder="Cari Kode item...">
    <input type="text" id="searchItemName" class="form-control flex-fill mx-1" placeholder="Cari Nama item...">
    <div class="flex-fill mx-1">
        <select id="category" class="form-select">
            <option value="all">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="d-flex ">
    <label class="form-label flex-fill">Tanggal Peminjaman</label>
</div>
<div class="d-flex mb-3">
    <input type="datetime-local" id="loan_date_start" class="form-control flex-fill me-2">
    <input type="datetime-local" id="loan_date_end" class="form-control flex-fill me-2">
</div>

<label class="form-label flex-fill">Tanggal Pengembalian</label>
<div class="d-flex mb-3">
    <input type="datetime-local" id="return_date_start" class="form-control flex-fill me-2">
    <input type="datetime-local" id="return_date_end" class="form-control flex-fill me-2">
</div>
<div class="d-flex mb-3">
    <div class="flex-fill mx-1">
        <select id="status_filter" class="form-select">
            <option value="all">All Status DPT</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
            <option value="returned">Returned</option>
        </select>
    </div>
    <div class="flex-fill mx-1">
        <select id="status_warek_filter" class="form-select">
            <option value="all">All Status Warek II</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
            <option value="returned">Returned</option>
        </select>
    </div>
</div>
<div class="d-flex mb-3">
    <input type="text" id="searchKeteranganPeminjam" class="form-control flex-fill mx-1" placeholder="Cari Keterangan Peminjam...">
    <input type="text" id="searchKeteranganPenyetuju" class="form-control flex-fill mx-1" placeholder="Cari Keterangan Penyetuju...">
</div>
</form>
<div class="table-responsive">
    <table class="table table-striped table-bordered" id="pinjams-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Kode Item</th>
                <th>Nama Item</th>
                <th>Kategori</th>
                <th>QTY</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Pengembalian</th>
                <th>Tanggal Pengembalian (asli)</th>
                <th>Status DPT</th>
                <th>Keterangan Peminjam</th>
                <th>Keterangan Penyetuju</th>
                <th>Status Warek II</th>
            </tr>
        </thead>
        <tbody id="pinjams-table-body">
            @foreach($pinjamDetails as $detail)
            <tr>
                <td>{{ $detail->pinjam->id }}</td>
                <td>{{ $detail->pinjam->user->name }}</td>
                <td>{{ $detail->pinjam->user->username }}</td>
                <td>{{ $detail->pinjam->user->phone }}</td>
                <td>{{ $detail->pinjam->user->role }}</td>
                <td>{{ $detail->item->kode_item }}</td>
                <td>{{ $detail->item->nama_item }}</td>
                <td>{{ $detail->item->category->name }}</td>
                <td>{{ $detail->qty }}</td>
                <td>{{ $detail->pinjam->loan_date }}</td>
                <td>{{ $detail->pinjam->return_date }}</td>
                <td>{{ $detail->pinjam->actual_return_date }}</td>
                <td>{{ ucfirst($detail->pinjam->status) }}</td>
                <td>{{ $detail->pinjam->keterangan_peminjam }}</td>
                <td>{{ $detail->pinjam->keterangan_penyetuju }}</td>
                <td>{{ $detail->pinjam->status_warek }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
@section('scripts')
<script>
    function fetchFilteredData() {
        const filters = {
            searchUserName: $('#searchUserName').val(),
            searchUserUsername: $('#searchUserUsername').val(),
            searchUserPhone: $('#searchUserPhone').val(),
            roleFilter: $('#roleFilter').val(),
            searchItemCode: $('#searchItemCode').val(),
            searchItemName: $('#searchItemName').val(),
            category: $('#category').val(),
            loan_date_start: $('#loan_date_start').val(),
            loan_date_end: $('#loan_date_end').val(),
            return_date_start: $('#return_date_start').val(),
            return_date_end: $('#return_date_end').val(),
            status_filter: $('#status_filter').val(),
            searchKeteranganPeminjam: $('#searchKeteranganPeminjam').val(),
            searchKeteranganPenyetuju: $('#searchKeteranganPenyetuju').val(),
            status_warek_filter: $('#status_warek_filter').val(),
        };

        // Update tabel dengan filter
        $.ajax({
            url: "{{ route('admin.reports.filter') }}",
            method: "GET",
            data: filters,
            success: function(response) {
                let rows = '';
                response.forEach(detail => {
                    rows += `
                        <tr>
                            <td>${detail.pinjam.id}</td>
                            <td>${detail.pinjam.user.name}</td>
                            <td>${detail.pinjam.user.username}</td>
                            <td>${detail.pinjam.user.phone}</td>
                            <td>${detail.pinjam.user.role}</td>
                            <td>${detail.item.kode_item}</td>
                            <td>${detail.item.nama_item}</td>
                            <td>${detail.item.category.name}</td>
                            <td>${detail.qty}</td>
                            <td>${detail.pinjam.loan_date}</td>
                            <td>${detail.pinjam.return_date}</td>
                            <td>${detail.pinjam.actual_return_date|| ''}</td>
                            <td>${detail.pinjam.status.charAt(0).toUpperCase() + detail.pinjam.status.slice(1)}</td>
                            <td>${detail.pinjam.keterangan_peminjam || ''}</td>
                            <td>${detail.pinjam.keterangan_penyetuju || ''}</td>
                            <td>${detail.pinjam.status_warek|| ''}</td>
                        </tr>`;
                });
                $('#pinjams-table-body').html(rows);
            }
        });
    }

    $('#exportForm').on('submit', function(e) {
        e.preventDefault(); // Mencegah pengiriman form default
        const filters = {
            searchUserName: $('#searchUserName').val(),
            searchUserUsername: $('#searchUserUsername').val(),
            searchUserPhone: $('#searchUserPhone').val(),
            roleFilter: $('#roleFilter').val(),
            searchItemCode: $('#searchItemCode').val(),
            searchItemName: $('#searchItemName').val(),
            category: $('#category').val(),
            loan_date_start: $('#loan_date_start').val(),
            loan_date_end: $('#loan_date_end').val(),
            return_date_start: $('#return_date_start').val(),
            return_date_end: $('#return_date_end').val(),
            status_filter: $('#status_filter').val(),
            searchKeteranganPeminjam: $('#searchKeteranganPeminjam').val(),
            searchKeteranganPenyetuju: $('#searchKeteranganPenyetuju').val(),
            status_warek_filter: $('#status_warek_filter').val()
        };
        const queryString = $.param(filters); // Ambil semua filter
        const actionUrl = "{{ route('admin.reports.export') }}?" + queryString; // Buat URL dengan filter
        window.location.href = actionUrl; // Arahkan ke URL untuk mengunduh
    });

    $('input, select').on('input change', fetchFilteredData);
</script>

@endsection
