<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\RefreshDatabase;


class StockAvailabilityTest extends DuskTestCase
{

    public function testStockAvailability()
    {
        $this->browse(function (Browser $browser) {
            // Navigate to the page
            $browser->visit('/') // Replace with the actual route for the feature
                ->assertSee('Cari Item') // Verify page has loaded
                // Fill in loan date and return date
                ->type('#loan_date', now()->addDays(1)->format('d-m-Y-TH:i')) // Use a future date
                ->type('#return_date', now()->addDays(5)->format('d-m-Y-TH:i')) // Ensure return date > loan date
                ->pause(500) // Wait for AJAX to process
                ->assertDontSee('Isi tanggal') // Ensure dates are valid
                ->assertDontSee('undifined')
                ->screenshot('stock_verified');
        });
    }

}
