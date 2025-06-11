<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EditUserTest extends DuskTestCase
{
    public function testEditUser(): void
    {
        // Membuat user dummy untuk diuji
        $user = User::create([
            'name' => 'John Doe',
            'username' => 'user-' . Str::random(8),
            'password' => bcrypt('password123'),
            'phone' => '6281234567890',
            'role' => 'peminjam',
            'status' => 'active',
            'keterangan' => 'Initial data',
        ]);

        // Mengunjungi halaman edit user
        $this->browse(function (Browser $browser) use ($user) {
            $this->loginAsAdmin($browser); // Login sebagai admin

            $browser->visit(route('admin.users.edit', $user->id))
                ->screenshot('edit_user')
                // Memeriksa bahwa halaman edit telah dimuat
                ->assertSee('Edit User')
                // Mengubah nilai di form edit
                ->type('name', 'Jane Doe')
                ->type('username', 'updated-' . Str::random(8))
                ->type('phone', '6289876543210')
                ->select('role', 'admin')
                ->select('status', 'inactive')
                ->type('keterangan', 'Updated user data')
                ->screenshot('edit_user2')
                // Mengirimkan form
                ->press('Update User')
                // Memeriksa bahwa perubahan berhasil disimpan dan kembali ke daftar user
                ->assertSee('User updated successfully.');
        });

        // Memastikan user di database telah terupdate
        $user->refresh();
        $this->assertEquals('Jane Doe', $user->name);
        $this->assertEquals('admin', $user->role);
        $this->assertEquals('inactive', $user->status);
        $this->assertEquals('Updated user data', $user->keterangan);
    }
}
