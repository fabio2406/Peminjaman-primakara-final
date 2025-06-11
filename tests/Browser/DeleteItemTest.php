<?php

namespace Tests\Browser;

use App\Models\Item;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DeleteItemTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testDeleteItem(): void
    {
        $item = Item::create([
            'kode_item' => 'ABC123unik',
            'nama_item' => 'Laptop',
            'stok' => 10,
            'category_id' => 1,
            'pengelola' => 'dpt',
        ]);

        // Mengunjungi halaman daftar item
        $this->browse(function (Browser $browser) use ($item) {
            $this->loginAsAdmin($browser);
            $browser->visit(route('admin.items.index'))
                ->type('#search', $item->kode_item)
                ->pause(1000)
                ->assertSee($item->kode_item)
                ->assertSee($item->nama_item)
                ->press('Hapus')
                ->pause(500)
                ->assertSee('Konfirmasi Hapus')
                ->press('Iya')
                ->refresh() 
                ->type('#search', $item->kode_item)
                ->assertDontSee($item->kode_item)
                ->assertDontSee($item->nama_item);
        });
        
        // Memastikan item di database telah terhapus
        $this->assertDatabaseMissing('items', [
            'kode_item' => $item->kode_item,
            'nama_item' => $item->nama_item,
        ]);
    }
}
