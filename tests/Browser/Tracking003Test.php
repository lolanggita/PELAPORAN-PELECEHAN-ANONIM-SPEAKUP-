<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class Tracking003Test extends DuskTestCase
{
    /**
     * TC.Tracking.003: User melihat kode tracking setelah submit berhasil
     */
    public function test_tracking003(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/lapor')
                    ->select('jenis_kejadian', 'Diskriminasi')
                    ->type('lokasi', 'Area Parkir')
                    ->type('deskripsi', 'Test see kode')
                    ->type('phone', '081234567891');
            
            $browser->script("document.getElementById('tanggal_kejadian').value = '2026-05-03T10:00';");

            $browser->press('button[type="submit"]')
                    ->waitForText('Kode Tracking Anda adalah', 10)
                    ->assertPathIs('/lapor/sukses')
                    ->assertSee('Kode Tracking Anda adalah: SU-');

        });
    }
}

