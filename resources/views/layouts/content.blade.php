
<div class="container">
        <!-- Form Pencarian -->
        <div class="mb-3">
            <h1 for="searchItem" class="form-label">Cari Item</h1>
        </div>

        <div class="container">
            <!-- Input pencarian item -->
            <input type="text" id="searchItem" class="form-control mb-3" placeholder="Cari item...">

            <!-- Label tanggal peminjaman dan pengembalian -->
            <div class="d-flex">
                <label class="form-label flex-fill me-2">Tanggal Peminjaman</label>
                <label class="form-label flex-fill ms-2">Tanggal Pengembalian</label>
            </div>

            <!-- Input tanggal peminjaman dan pengembalian -->
            <div class="mb-3 d-flex justify-items-between">
                <input type="datetime-local" class="form-control me-2" id="loan_date" name="loan_date" required>
                <input type="datetime-local" class="form-control ms-2" id="return_date" name="return_date" required>
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
            <!-- Tabel daftar item -->
            <table class="table  table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Kode Item</th>
                        <th>Item</th>
                        <th>Category</th>
                        <th>Pengelola</th>
                        <th>Stock Available</th>
                    </tr>
                </thead>
                <tbody id="available-items">
                </tbody>
            </table>
            <div id="pagination-container" ></div>
        </div>
    </div>

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

    // Fungsi untuk memperbarui stok berdasarkan tanggal peminjaman dan pengembalian
    function updateStockAvailability() {
        var loanDate = $('#loan_date').val();
        var returnDate = $('#return_date').val();

        if (loanDate && returnDate) {
            $.ajax({
                url: "{{ route('getAvailableItems') }}", // Pastikan route ini sesuai
                method: "GET",
                data: { loan_date: loanDate, return_date: returnDate },
                success: function(response) {
                    // Update stok di modal (available-items)
                    $('#available-items tr').each(function() {
                        var itemId = $(this).find('.stock-available').data('item-id');
                        var availableStock = response[itemId] !== undefined ? response[itemId] : 0;
                        $(this).find('.stock-available').text(availableStock);
                    });
                },
                error: function(xhr) {
                    console.error(xhr);
                    alert('Terjadi kesalahan saat memeriksa stok.');
                }
            });
        }else {
            // Jika tanggal belum dipilih, set stok ke 0
            $('#available-items tr').each(function() {
                $(this).find('.stock-available').text('Isi tanggal');
            });
        }
    }
    
    // Panggil fungsi untuk update stok saat tanggal peminjaman atau pengembalian berubah
    $('#loan_date, #return_date').on('change', function() {
        validateDates();
        updateStockAvailability();
    });

    // Fungsi untuk memuat ulang data item berdasarkan pencarian dan filter
    // Fungsi untuk memuat ulang data item berdasarkan pencarian, filter, dan halaman
    function loadItems(page = 1) {
        var searchQuery = $('#searchItem').val();
        var category = $('#category').val();
        var pengelola = $('#pengelola').val();
        var loanDate = $('#loan_date').val();
        var returnDate = $('#return_date').val();

        // Lakukan AJAX request ke server
        $.ajax({
            url: "{{ route('filter') }}", // Ganti dengan route filter item
            method: "GET",
            data: {
                search: searchQuery,
                category: category,
                pengelola: pengelola,
                loan_date: loanDate,
                return_date: returnDate,
                page: page // Tambahkan parameter halaman
            },
            success: function(response) {
                // Kosongkan tabel item
                $('#available-items').empty();

                // Tambahkan data item baru ke tabel
                $.each(response.items, function(index, item) {
                    $('#available-items').append(`
                        <tr>
                            <td>${item.kode_item}</td>
                            <td>${item.nama_item}</td>
                            <td>${item.category.name}</td>
                            <td>${item.pengelola.toUpperCase()}</td>
                            <td class="stock-available" data-item-id="${item.id}">${item.stock_available}</td>
                        </tr>
                    `);
                });

                // Tampilkan pagination di container
                $('#pagination-container').html(response.pagination);

                // Perbarui stok berdasarkan tanggal peminjaman dan pengembalian
                updateStockAvailability();
            },
            error: function(xhr) {
                console.error(xhr);
                alert('Terjadi kesalahan saat memuat data item.');
            }
        });
    }

    // Menangani klik pada pagination
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();

        // Ambil nomor halaman dari link pagination
        var page = $(this).attr('href').split('page=')[1];

        // Muat item untuk halaman yang dipilih
        loadItems(page);
    });

    // Panggil loadItems() setiap kali input pencarian atau filter diubah
    $('#searchItem, #category, #pengelola, #loan_date, #return_date').on('input change', function() {
        loadItems();
    });

    loadItems();
});

</script>
@endsection
