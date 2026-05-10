<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class Tracking002Test extends DuskTestCase
{
    /**
     * TC.Tracking.002: Submit tanpa data lengkap (Negative)
     */
    public function test_tracking002(): void
    {
        $this->browse(function (Browser $browser) {
            // 1. Launch & open form
            $browser->visit('/lapor')
                ->type('lokasi', 'Lokasi only')
                ->press('button[type="submit"]');


            $message = $browser->script("return document.querySelector('#jenis_kejadian').validationMessage;")[0];
            $this->assertNotEmpty($message);

            $browser->assertPathIs('/lapor')
                ->screenshot('tc002_validation_errors');
        });
    }
}
