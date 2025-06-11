@extends('layouts.warek')

@section('content')
<div class="container">
    <h1>Log Aktivitas</h1>

    <div class="mb-3">
        <input type="text" id="search" class="form-control" placeholder="Cari Username atau Id peminjaman" value="{{ request('search') }}">
    </div>
    <select id="activity" class="form-select mb-3">
        <option value="all">All Activity</option>
        <option value="create">Create</option>
        <option value="update">Update</option>
        <option value="update status">Update Status</option>
        <option value="delete">Delete</option>
    </select>
    <div class="d-flex justify-items-between">
        <label for="from_date" class="form-label flex-fill me-2">Tanggal Dari</label>
        <label for="until_date" class="form-label flex-fill ms-2">Tanggal Sampai</label>
    </div>
    <div class="mb-3 d-flex justify-items-between">
        <input type="datetime-local" class="form-control flex-fill me-2" id="from_date" name="from_date">
        <input type="datetime-local" class="form-control flex-fill ms-2" id="until_date" name="until_date">
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Username Pengguna</th>
                <th>Id Peminjaman</th>
                <th>Tipe Aktivitas</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody id="logs-body">
            @foreach($logs as $log)
            <tr>
                <td>{{ $log->user->username ?? 'N/A' }}</td>
                <td>{{ $log->pinjam_id }}</td>
                <td>{{ ucfirst($log->activity_type) }}</td>
                <td>{{ $log->created_at->format('d/m/Y, H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination-container">
        {!! $logs->links('pagination::bootstrap-5') !!}
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#search').on('keyup', function() {
        filterLogs();
    });

    $('#activity').on('change', function() {
        filterLogs();
    });

    $('#from_date, #until_date').on('change', function() {
        filterLogs();
    });

    function filterLogs(url = "{{ route('warek.logs.index') }}") {
        let searchValue = $('#search').val();
        let activityType = $('#activity').val();
        let fromDate = $('#from_date').val();
        let untilDate = $('#until_date').val();

        $.ajax({
            url: url,
            method: "GET",
            data: { 
                search: searchValue, 
                activity_type: activityType,
                from_date: fromDate,
                until_date: untilDate
            },
            success: function(data) {
                let rows = '';
                data.logs.data.forEach(log => {
                    rows += `
                        <tr>
                            <td>${log.user?.username ?? 'N/A'}</td>
                            <td>${log.pinjam_id}</td>
                            <td>${log.activity_type.charAt(0).toUpperCase() + log.activity_type.slice(1)}</td>
                            <td>${new Date(log.created_at).toLocaleString()}</td>
                        </tr>
                    `;
                });
                $('#logs-body').html(rows);
                $('.pagination-container').html(data.pagination);

                // Log filters untuk debugging
                console.log('Filters applied:', data.filters);
            },
            error: function(xhr, status, error) {
                console.error(xhr);
            }
        });
    }

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        filterLogs(url);
    });
});
</script>
@endsection