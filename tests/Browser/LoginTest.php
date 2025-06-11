<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends DuskTestCase
{

    public function testWrongLogin(): void
    {

        $this->browse(function (Browser $browser) {
            $browser->visit('/logout')
            ->visit('/login')
            ->assertSee('Login') // Pastikan teks 'Login' terlihat
            ->type('username', 'wronguser')
            ->type('password', 'wrongpassword')
            ->press('Masuk')
            ->assertSee('Login failed. Please check your credentials and try again.');
        });
    }
    public function testLoginPeminjam(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/logout')
            ->visit('/login')
            ->assertSee('Login') // Pastikan teks 'Login' terlihat
            ->type('username', 'peminjam') // Isi username
            ->type('password', 'password') // Isi password
            ->press('Masuk') // Klik tombol 'Masuk'
            ->assertPathIs('/peminjam/dashboard') // Pastikan diarahkan ke halaman /home
            ->assertSee('Dashboard Peminjam'); // Pastikan teks 'Dashboard' terlihat
        });
    }
    public function testLoginAdmin(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/logout')
            ->visit('/login')
            ->assertSee('Login') // Pastikan teks 'Login' terlihat
            ->type('username', 'admin') // Isi username
            ->type('password', 'password') // Isi password
            ->press('Masuk') // Klik tombol 'Masuk'
            ->assertPathIs('/admin/dashboard') // Pastikan diarahkan ke halaman /home
            ->assertSee('Dashboard Admin'); // Pastikan teks 'Dashboard' terlihat
        });
    }

    public function testLoginPenyetuju(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/logout')
            ->visit('/login')
            ->assertSee('Login') // Pastikan teks 'Login' terlihat
            ->type('username', 'dala') // Isi username
            ->type('password', 'password') // Isi password
            ->press('Masuk') // Klik tombol 'Masuk'
            ->assertPathIs('/dala/dashboard') // Pastikan diarahkan ke halaman /home
            ->assertSee('Dashboard DALA'); // Pastikan teks 'Dashboard' terlihat
        });
    }
}
