@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<form action="{{ url('admin/export-pinjams') }}" method="GET" class="form-inline mb-3">
    <div class="form-group mr-2">
        <label for="month" class="mr-2">Bulan:</label>
        <select name="month" id="month" class="form-control">
            <option value="">Semua</option>
            @foreach (range(1, 12) as $month)
                <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 10)) }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mr-2">
        <label for="year" class="mr-2">Tahun:</label>
        <select name="year" id="year" class="form-control">
            <option value="">Semua</option>
            @foreach (range(date('Y'), date('Y')+10) as $year)
                <option value="{{ $year }}">{{ $year }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Export</button>
</form>
@endsection
