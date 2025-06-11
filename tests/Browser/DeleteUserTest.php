<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DeleteUserTest extends DuskTestCase
{
    public function testDeleteUser(): void
    {
        // Membuat user dummy untuk diuji
        $user = User::create([
            'name' => 'John Doe',
            'username' => 'delete-user-test',
            'password' => bcrypt('password123'),
            'phone' => '6281234567890',
            'role' => 'peminjam',
            'status' => 'active',
            'keterangan' => 'Test data for deletion',
        ]);

        // Mengunjungi halaman daftar user
        $this->browse(function (Browser $browser) use ($user) {
            $this->loginAsAdmin($browser);
            $browser->visit(route('admin.users.index')) // Ganti dengan route untuk halaman daftar user
                ->type('#search', $user->username) // Kolom pencarian username
                ->pause(1000) // Tunggu hasil pencarian
                ->assertSee($user->username) // Pastikan user ditemukan
                ->assertSee($user->name)
                ->press('Hapus') // Tombol hapus
                ->pause(500) // Tunggu modal konfirmasi
                ->assertSee('Konfirmasi Hapus') // Modal konfirmasi hapus
                ->press('Iya') // Tombol konfirmasi hapus
                ->refresh() // Segarkan halaman
                ->type('#search', $user->username) // Cari kembali user
                ->assertDontSee($user->username); // Pastikan user tidak terlihat
        });

        // Memastikan user di database telah terhapus
        $this->assertDatabaseMissing('users', [
            'username' => $user->username
        ]);
    }
}
