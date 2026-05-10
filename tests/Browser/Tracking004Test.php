<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class Tracking004Test extends DuskTestCase
{
    /**
     * TC.Tracking.004: User membuka halaman tracking (Positive - enter code)
     */
    public function test_tracking004(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/track')
                    ->assertSee('Cek Status Laporan')
                    ->type('kode_tracking', 'SU-TEST123')
                    ->press('button[type="submit"]');
        });
    }
}

