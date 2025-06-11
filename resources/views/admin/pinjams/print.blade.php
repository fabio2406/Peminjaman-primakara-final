<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cetak Peminjaman</title>
    <style type="text/css">
        @font-face {
            font-family: 'XDPrime';
            src: url('{{ public_path('fonts/XDPrime-Medium.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        
        @font-face {
            font-family: 'XDPrime Bold';
            src: url('{{ public_path('fonts/XDPrime-Bold.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'XDPrime';
        }
        strong {
            font-family: 'XDPrime Bold';
        }
        table {
            font-size: x-small;
            width: 100%;
            border-collapse: collapse;
        }
        p{
            font-size: x-small;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 4px;
        }
        .gray {
            background-color: lightgray;
        }
        .signature-section {
            margin-top: 50px;
            padding: 0 50px;
            position: relative;
        }
        .signature {
            margin-top: 60px;
            display: inline-block;
            width: 200px;
            border-top: 1px solid #000;
            text-align: center;
        }
        .left-signature {
            position: absolute;
            left: 50px;
        }
        .right-signature {
            position: absolute;
            right: 50px;
        }
    </style>
</head>
<body>

<img src="{{ public_path('img/logo.png') }}" alt="Logo" style="max-width: 100px;"/>

<h2 align="center">Detail Peminjaman Barang</h2>
<p><strong>NIM/Username:</strong> {{ $pinjam->user->username }}</p>
<p><strong>Nama:</strong> {{ $pinjam->user->name }}</p>
<p><strong>Instansi:</strong> {{ ucfirst($pinjam->instansi) }}</p>
<p><strong>No telepon:</strong> {{ $pinjam->user->phone }}</p>
<p><strong>Status DPT:</strong> {{ ucfirst($pinjam->status) }}</p>
@if($pinjam->status_sdm != null)
<p><strong>Status SDM:</strong> {{ ucfirst($pinjam->status_sdm) }}</p>
@endif
@if($pinjam->status_dala != null)
<p><strong>Status DALA:</strong> {{ ucfirst($pinjam->status_dala) }}</p>
@endif
@if($pinjam->status_warek != null)
<p><strong>Status Warek II:</strong> {{ ucfirst($pinjam->status_warek) }}</p>
@endif
<table>
    <tr>
        <td style="border : 0px;"><strong>Tanggal Pinjam:</strong> {{ $pinjam->loan_date }}</td>
        <td style="border : 0px;"><strong>Tanggal Pengembalian:</strong> {{ $pinjam->return_date }}</td>
    </tr>
</table>

<br/>

<table class="item">
    <thead class="gray">
        <tr>
            <th>Kode Item</th>
            <th>Nama Item</th>
            <th>Kategori</th>
            <th>Jumlah Pinjam</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pinjam->details as $detail)
            <tr>
                <td>{{ $detail->item->kode_item }}</td>
                <td>{{ $detail->item->nama_item }}</td>
                <td>{{ $detail->item->category->name }}</td>
                <td>{{ $detail->qty }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<p>Keterangan Peminjam: {{ $pinjam->keterangan_peminjam }}</p>
<p>Keterangan Penyetuju: {{ $pinjam->keterangan_penyetuju }}</p>


</body>
</html>
