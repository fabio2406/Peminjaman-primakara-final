@extends('layouts.peminjam')
@section('title', 'Create Peminjaman')
@section('content')
<a href="{{ url('peminjam/pinjams') }}" class="btn btn-primary mb-3">Kembali</a>
<h2>Peminjaman Barang</h2>

<form action="{{ route('peminjam.pinjams.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="user_id" class="form-label">Meminjam sebagai : {{ $users->name }}</label>
    </div>

    <div class="mb-3">
        <label for="instansi" class="form-label">Instansi</label>
        <input type="text" class="form-control" id="instansi" name="instansi" required>
    </div>

    <!-- Label tanggal peminjaman dan pengembalian -->
    <div class="d-flex">
        <label for="loan_date" class="form-label flex-fill me-2">Tanggal Peminjaman</label>
        <label for="return_date" class="form-label flex-fill ms-2">Tanggal Pengembalian</label>
    </div>
    <div class="mb-3 d-flex justify-items-between">
        <input type="datetime-local" class="form-control flex-fill me-2" id="loan_date" name="loan_date" required>
        <input type="datetime-local" class="form-control flex-fill ms-2" id="return_date" name="return_date" required>
    </div>

    <div class="">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#itemModal">Tambah Item</button>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Kode Item</th> <!-- Kolom kode_item -->
                <th>Nama Item</th>
                <th>Pengelola</th>
                <th class="col-1">Stock Available</th>
                <th class="col-1">Jumlah Pinjam</th>
                <th class="col-1">Aksi</th>
            </tr>
        </thead>
        <tbody id="item-list">
            <!-- Daftar item akan ditambahkan di sini -->
        </tbody>
    </table>
    <div class="mb-3">
        <label for="keterangan_peminjam" class="form-label">Keterangan</label>
        <textarea class="form-control" id="keterangan_peminjam" name="keterangan_peminjam"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Peminjaman</button>
</form>

<!-- Modal untuk memilih item -->
<div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemModalLabel">Pilih Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form Pencarian -->
                <div class="mb-3">
                    <label for="searchItem" class="form-label">Cari Item</label>
                    <input type="text" id="searchItem" class="form-control" placeholder="Cari item...">
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Kode Item</th> <!-- Tambahkan Kode Item -->
                            <th>Item</th>
                            <th>pengelola</th>
                            <th>Stock Available</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="available-items">
                        @foreach($items as $item)
                            <tr>
                                <td class="item-code">{{ $item->kode_item }}</td> <!-- Kode Item -->
                                <td class="item-name">{{ $item->nama_item }}</td>
                                <td class="item-pengelola">{{ $item->pengelola }}</td>
                                <td class="stock-available" data-item-id="{{ $item->id }}">Loading...</td>
                                <td>
                                    <button class="btn btn-success add-item" data-id="{{ $item->id }}" data-name="{{ $item->nama_item }}" data-pengelola="{{ $item->pengelola }}">Pilih</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Fungsi untuk validasi tanggal kembali
    function validateDates() {
        let loanDate = new Date($('#loan_date').val());
        let returnDate = new Date($('#return_date').val());
        if (returnDate < loanDate) {
            alert('Tanggal kembali tidak boleh lebih awal dari tanggal pinjam.');
            $('#return_date').val(''); // Reset nilai input return_date
        }
    }

    // Fungsi untuk update stok berdasarkan tanggal peminjaman dan pengembalian
    function updateStockAvailability() {
        let loanDate = $('#loan_date').val();
        let returnDate = $('#return_date').val();

        if (loanDate && returnDate) {
            $.ajax({
                url: "{{ route('peminjam.pinjams.getAvailableItems') }}", // Pastikan route ini sesuai
                method: "GET",
                data: { loan_date: loanDate, return_date: returnDate },
                success: function(response) {
                    // Update stok di modal (available-items)
                    $('#available-items tr').each(function() {
                        let itemId = $(this).find('.stock-available').data('item-id');
                        let availableStock = response[itemId] !== undefined ? response[itemId] : 0;
                        $(this).find('.stock-available').text(availableStock);
                        if(availableStock == 0){
                            $(this).find('.add-item').prop('disabled', true).addClass('btn-danger').removeClass('btn-success');
                        }else {
                            $(this).find('.add-item').prop('disabled', false).addClass('btn-success').removeClass('btn-secondary');
                        }
                    });

                    // Update stok di tabel item-list (jika item sudah dipilih)
                    $('#item-list tr').each(function() {
                        let itemId = $(this).find('input[name$="[item_id]"]').val(); // Ambil item_id dari input hidden
                        if (response[itemId] !== undefined) {
                            $(this).find('.stock-available').text(response[itemId]); // Update stok yang tersedia
                            $(this).find('.qty-input').data('stock', response[itemId]);
                        }
                    });
                },
                error: function(xhr) {
                    console.error(xhr);
                    alert('Terjadi kesalahan saat memeriksa stok.');
                }
            });
        }
    }

    // Panggil fungsi untuk update stok saat tanggal peminjaman atau pengembalian berubah
    $('#loan_date, #return_date').on('change', function() {
        validateDates();
        updateStockAvailability();
    });

    // Update stok saat modal dibuka
    $('#itemModal').on('show.bs.modal', function () {
        updateStockAvailability(); // Memanggil fungsi yang sama saat modal dibuka
    });

    // Filter item berdasarkan input pencarian
    $('#searchItem').on('keyup', function() {
        let searchValue = $(this).val().toLowerCase();
        $('#available-items tr').filter(function() {
            // Filter berdasarkan nama item dan kode item
            $(this).toggle(
                $(this).find('.item-name').text().toLowerCase().indexOf(searchValue) > -1 || 
                $(this).find('.item-code').text().toLowerCase().indexOf(searchValue) > -1
            );
        });
    });

    // Tambahkan item ke tabel ketika tombol "Pilih" ditekan
    $('.add-item').on('click', function() {
        let itemId = $(this).data('id');
        let itemName = $(this).data('name');
        let itemPengelola = $(this).data('pengelola');
        let stockAvailable = $(this).closest('tr').find('.stock-available').text(); // Ambil stok yang tersedia dari tabel
        let itemCode = $(this).closest('tr').find('.item-code').text(); // Ambil kode_item dari tabel

        // Cek apakah item sudah ada di tabel #item-list
        if ($('#item-list').find(`input[name="items[${itemId}][item_id]"]`).length > 0) {
            alert('Item ini sudah dipilih!');
            return; // Jangan tambahkan item jika sudah ada
        }

        // Tambahkan baris baru di tabel item-list
        $('#item-list').append(`
            <tr>
                <td>${itemCode}</td> <!-- Tambahkan kode_item di sini -->
                <td>${itemName}</td>
                <td>${itemPengelola}</td>
                <td class="stock-available">${stockAvailable}</td>
                <td><input type="number" name="items[${itemId}][qty]" min="1" class="qty-input" style='max-width: 50px;' data-stock="${stockAvailable}" required></td>
                <td><button type="button" class="btn btn-danger remove-item">Hapus</button></td>
                <input type="hidden" name="items[${itemId}][item_id]" value="${itemId}">
            </tr>
        `);

        // Tutup modal
        $('#itemModal').modal('hide');
    });

    // Hapus item dari tabel
    $('#item-list').on('click', '.remove-item', function() {
        $(this).closest('tr').remove();
    });

    // Cek stok saat qty diinput
    $('#item-list').on('input', '.qty-input', function() {
        let qty = $(this).val();
        let stockAvailable = $(this).data('stock');

        if (qty > stockAvailable) {
            alert(`Stok tidak mencukupi untuk item ini. Stok tersedia: ${stockAvailable}`);
            $(this).val(stockAvailable); // Reset nilai qty ke stok yang tersedia
        }
    });
});
</script>
@endsection
