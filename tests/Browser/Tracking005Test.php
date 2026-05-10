<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class Tracking005Test extends DuskTestCase
{
    /**
     * TC.Tracking.005: Track kosong - validation error
     */
    public function test_tracking005(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/track')
                    ->press('button[type="submit"]');


            $message = $browser->script("return document.querySelector('#kode_tracking').validationMessage;")[0];
            $this->assertNotEmpty($message);

            $browser->assertPathIs('/track')
                    ->screenshot('tc005_empty_validation');
        });
    }
}

