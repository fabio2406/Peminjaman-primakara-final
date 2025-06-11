@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<a href="{{ url('admin/users') }}" class="btn btn-primary mb-3">Kembali</a>
    <h2>Edit User</h2>


    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input id="name" type="text" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input id="username" type="text" name="username" class="form-control" value="{{ $user->username }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password (leave blank to keep current password)</label>
            <input id="password" type="password" name="password" class="form-control">
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control">
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone (awali dengan 62)</label>
            <input id="phone" type="text" name="phone" class="form-control" value="{{ $user->phone }}" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="peminjam" {{ $user->role == 'peminjam' ? 'selected' : '' }}>Peminjam</option>
                <option value="sdm" {{ $user->role == 'sdm' ? 'selected' : '' }}>SDM</option>
                <option value="dala" {{ $user->role == 'dala' ? 'selected' : '' }}>DALA</option>
                <option value="warek" {{ $user->role == 'warek' ? 'selected' : '' }}>WAREK II</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">keterangan</label>
            <input id="keterangan" type="text" name="keterangan" class="form-control" value="{{ $user->keterangan }}">
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
@endsection
