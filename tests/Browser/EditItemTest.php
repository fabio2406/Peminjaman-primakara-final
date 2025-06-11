<?php

namespace Tests\Browser;

use App\Models\Item;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EditItemTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testEditItem(): void
    {
        $randomKodeItem = 'unik-' . Str::random(8); 
        $randomKodeItem2 = 'ITEM-' . Str::random(4); 
        // Membuat kategori dan item yang ada
        $item = Item::create([
            'kode_item' => $randomKodeItem,
            'nama_item' => 'Laptop',
            'stok' => 10,
            'category_id' => 1,
            'pengelola' => 'dpt',
        ]);

        // Mengunjungi halaman edit item
        $this->browse(function (Browser $browser) use ($item, $randomKodeItem2) {
            $this->loginAsAdmin($browser);// Login sebagai admin
            $browser->visit(route('admin.items.edit', $item->id))
                ->screenshot('item')
                // Memeriksa bahwa halaman edit telah dimuat
                ->assertSee('Edit Item')
                // Mengubah nilai di form edit
                ->type('kode_item', $randomKodeItem2)
                ->type('nama_item', 'Desktop')
                ->screenshot('item2')
                ->type('stok', '5')
                ->select('category_id', '2')
                ->select('pengelola', 'sdm')
                // Mengirimkan form
                ->press('Update')
                // Memeriksa bahwa perubahan berhasil disimpan dan kembali ke daftar item
                ->assertSee('Item updated successfully.');
        });

        // Memastikan item di database telah terupdate
        $item->refresh();
        $this->assertEquals($randomKodeItem2, $item->kode_item);
        $this->assertEquals('Desktop', $item->nama_item);
        $this->assertEquals(5, $item->stok);
        $this->assertEquals('sdm', $item->pengelola);
    }
}
