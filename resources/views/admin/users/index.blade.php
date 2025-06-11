@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<h1>Users</h1>

<div class="mb-3">
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Create New User</a>
</div>

<!-- Input Pencarian -->
<div class="mb-3">
    <input type="text" id="search" class="form-control" placeholder="Search by name, username or phone">
</div>
<div class="d-flex mb-3">
<!-- Filter Role -->
<div class="flex-fill me-2">
    <select id="roleFilter" class="form-select">
        <option value="all">All Roles</option>
        <option value="admin">Admin</option>
        <option value="peminjam">Peminjam</option>
        <option value="sdm">SDM</option>
        <option value="dala">DALA</option>
        <option value="warek">WAREK II</option>
    </select>
</div>

<!-- Filter Status -->
<div class="flex-fill ms-2">
    <select id="statusFilter" class="form-select">
        <option value="all">All Statuses</option>
        @foreach($statuses as $status)
            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
        @endforeach
    </select>
</div>
</div>
<div class="table-responsive">
    <table class="table table-striped table-bordered" id="user-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Phone</th>
                <th class="col-1">Role</th>
                <th class="col-1">Status</th>
                <th class="col-3">Keterangan</th>
                <th class="col-3">Actions</th>
            </tr>
        </thead>
        <tbody id="user-table-body">
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>
                        @if($user->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $user->keterangan }}</td>    
                    <td class="d-flex justify-content-between">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('admin.users.toggleStatus', $user->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('PUT')
                            
                                @if($user->status == 'active')
                                    <button type="submit" class="btn btn-secondary">Set Inactive </button>
                                @else
                                    <button type="submit" class="btn btn-primary">Set active </button>
                                @endif
                            
                        </form>
                        <button type="button" class="btn btn-danger" onclick="openDeleteModal('{{ $user->id }}')">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="pagination-container">
    {!! $users->links('pagination::bootstrap-5') !!}
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
                    Apakah Anda yakin ingin menghapus user ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Iya</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    $('#search').on('keyup', function() {
        filterUsers();
    });

    $('#roleFilter, #statusFilter').on('change', function() {
        filterUsers();
    });

    function filterUsers(url = "{{ route('admin.users.index') }}") {
        let searchValue = $('#search').val();
        let roleValue = $('#roleFilter').val();
        let statusValue = $('#statusFilter').val();

        $.ajax({
            url: url,
            method: "GET",
            data: { search: searchValue, role: roleValue, status: statusValue },
            success: function(data) {
                let rows = '';
                data.users.data.forEach(user => {
                    rows += `
                        <tr>
                            <td>${user.name}</td>
                            <td>${user.username}</td>
                            <td>${user.phone}</td>
                            <td>${user.role.charAt(0).toUpperCase() + user.role.slice(1)}</td>
                            <td><span class="badge bg-${user.status === 'active' ? 'success' : 'danger'}">${user.status.charAt(0).toUpperCase() + user.status.slice(1)}</span></td>
                            <td>${user.keterangan|| ''}</td>
                            <td class="d-flex justify-content-between">
                                <a href="/admin/users/${user.id}/edit" class="btn btn-warning">Edit</a>
                                <form action="/admin/users/${user.id}/toggleStatus" method="POST" style="display:inline-block;">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="PUT">
                                    <button type="submit" class="btn btn-secondary">${user.status === 'active' ? 'Set Inactive' : 'Set Active'}</button>
                                </form>
                                <button type="button" class="btn btn-danger mx-1" onclick="openDeleteModal(${user.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
                $('#user-table-body').html(rows);
                $('.pagination-container').html(data.pagination);
            },
            error: function(xhr, status, error) {
                console.error(xhr);
            }
        });
    }

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        filterUsers(url);
    });
});

function openDeleteModal(userId) {
    $('#deleteForm').attr('action', `/admin/users/${userId}`);  // Ubah URL form sesuai dengan id peminjaman
    $('#deleteModal').modal('show');  // Tampilkan modal konfirmasi hapus
}
</script>

@endsection
