<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends DuskTestCase
{
    
    
    /**
     * Test registration page loads correctly.
     */
    public function testRegistrationPageLoads()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->assertSee('Register')
                    ->assertPresent('form');
        });
    }

    /**
     * Test successful registration.
     */
    public function testSuccessfulRegistration()
    {
        $randomUsername = 'USER-' . Str::random(8); // Simpan username di variabel lokal

        $this->browse(function (Browser $browser) use ($randomUsername) {
            $browser->visit('/register')
                    ->type('name', 'Test User')
                    ->type('username', $randomUsername)
                    ->type('password', 'password123')
                    ->type('password_confirmation', 'password123')
                    ->type('phone', '628123456789')
                    ->press('Daftar')
                    ->assertPathIs('/login')
                    ->assertSee('Registration successful!');
        });
    
        // Gunakan variabel lokal untuk memverifikasi data di database
        $this->assertDatabaseHas('users', [
            'username' => $randomUsername,
            'name' => 'Test User',
            'status' => 'inactive',
            'role' => 'peminjam',
        ]);
    }

    /**
     * Test validation errors on registration.
     */
    public function testValidationErrors()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->script([
                        "document.querySelectorAll('[required]').forEach(el => el.removeAttribute('required'))"
                    ]);
    
            $browser->type('name', '') // Empty name
                    ->type('username', 'short') 
                    ->type('password', 'short') // Too short
                    ->type('password_confirmation', 'different') // Does not match
                    ->press('Daftar')
                    ->assertSee('The name field is required.')
                    ->assertSee('The password field must be at least 8 characters.')
                    ->assertSee('The password field confirmation does not match.');
        });
    }
}
