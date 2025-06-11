<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateUserTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testCreateNewUser()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser); // Login sebagai admin
            $randomUsername = 'user-' . Str::random(8); // Membuat username unik
            $randomPhone = '628' . rand(1000000000, 9999999999); // Membuat nomor telepon unik

            $browser->visit('/admin/users/create') // Ganti dengan route halaman ini
                ->assertSee('Create User') // Pastikan halaman dimuat

                // Isi formulir
                ->type('name', 'John Doe')
                ->type('username', $randomUsername)
                ->type('password', 'password123')
                ->type('password_confirmation', 'password123')
                ->type('phone', $randomPhone)
                ->select('role', 'admin')
                ->select('status', 'active')
                ->type('keterangan', 'Admin User')

                // Kirim formulir
                ->press('Create User') // Tombol untuk submit
                ->assertPathIs('/admin/users') // Redirect setelah berhasil
                ->assertSee('User created successfully.'); // Flash message atau teks sukses
        });
    }


    public function testValidationErrors()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser); // Login sebagai admin

            $browser->visit('/admin/users/create') // Ganti dengan route halaman ini
                ->assertSee('Create User') // Pastikan halaman dimuat
                ->script([
                    "document.querySelectorAll('[required]').forEach(el => el.removeAttribute('required'))"
                ]);

            // Kosongkan input dan kirim formulir
            $browser->press('Create User')
                ->assertPathIs('/admin/users/create') // Tetap di halaman yang sama karena validasi gagal
                ->assertSee('The name field is required.') // Pesan validasi
                ->assertSee('The username field is required.')
                ->assertSee('The password field is required.')
                ->assertSee('The role field is required.');
        });
    }
}
