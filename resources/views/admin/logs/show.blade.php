@extends('layouts.admin')

@section('content')
<div class="container">
    <a href="{{ url('admin/logs') }}" class="btn btn-primary mb-3">Kembali</a>
    <h2 class="mb-4">Detail Perubahan Aktivitas</h2>

    <div class="row">
        <!-- Data Sebelum -->
        <div class="col-md-6">
            <h4>Data Sebelum</h4>
            <table class="table table-bordered table-striped">
                <tbody>
                    @forelse($changes['before'] ?? [] as $key => $value)
                        <tr>
                            <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                            <td>{{ $value ?? 'null' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Data Sesudah -->
        <div class="col-md-6">
            <h4>Data Sesudah</h4>
            <table class="table table-bordered table-striped">
                <tbody>
                    @forelse($changes['after'] ?? [] as $key => $value)
                        @php
                            // Ambil nilai "before" dari key saat ini
                            $beforeValue = $changes['before'][$key] ?? null;
                            // Periksa apakah before ada dan berbeda dari after
                            $isChanged = !is_null($beforeValue) && $beforeValue !== $value;
                        @endphp
                        <tr>
                            <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                            <td class="{{ $isChanged ? 'bg-warning' : '' }}">
                                {{ $value ?? 'null' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
