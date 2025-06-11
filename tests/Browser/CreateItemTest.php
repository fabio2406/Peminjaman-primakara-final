<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class CreateItemTest extends DuskTestCase
{

    public function testCreateNewItem()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);// Login sebagai admin
            $randomKodeItem = 'ITEM-' . Str::random(8); // Membuat kode item unik
            $browser ->visit('/admin/items/create') // Ganti dengan route untuk halaman ini
                ->assertSee('Create Item') // Pastikan halaman dimuat

                // Isi formulir
                ->type('kode_item', $randomKodeItem)
                ->type('nama_item', 'Laptop')
                ->type('stok', '10')
                ->select('category_id', '1')
                ->select('pengelola', 'dpt')

                // Kirim formulir
                ->press('Create')// Redirect setelah berhasil
                ->assertSee('Item created successfully.'); // Flash message atau teks hasil sukses
        });
    }


    public function testValidationErrors()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/items/create') // Ganti dengan route untuk halaman ini
                ->assertSee('Create Item') // Pastikan halaman dimuat
                ->script([
                    "document.querySelectorAll('[required]').forEach(el => el.removeAttribute('required'))"
                ]);

                // Kosongkan input dan kirim formulir
            $browser->press('Create')
                ->assertPathIs('/admin/items/create') // Tetap di halaman yang sama karena validasi gagal
                ->assertSee('The kode item field is required.') // Pesan validasi
                ->assertSee('The nama item field is required.')
                ->assertSee('The stok field is required.')
                ->assertSee('The category id field is required.')
                ->assertSee('The pengelola field is required.');
        });
    }
}