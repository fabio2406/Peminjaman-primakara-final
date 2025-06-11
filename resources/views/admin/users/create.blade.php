@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
<a href="{{ url('admin/users') }}" class="btn btn-primary mb-3">Kembali</a>
    <h2>Create User</h2>
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input id="name" type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input id="username" type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label" required>Phone (awali dengan 62)</label>
            <input id="phone" type="text" name="phone" class="form-control">
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value=""> Select Roles</option>
                <option value="admin">Admin</option>
                <option value="peminjam">Peminjam</option>
                <option value="sdm">SDM</option>
                <option value="dala">DALA</option>
                <option value="warek">WAREK II</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <input id="keterangan" type="text" name="keterangan" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Create User</button>
    </form>
@endsection
