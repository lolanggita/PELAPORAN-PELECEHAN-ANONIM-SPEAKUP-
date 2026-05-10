<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class Tracking006Test extends DuskTestCase
{
    /**
     * TC.Tracking.006: Cek status dengan kode valid
     */
    public function test_tracking006(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/lapor')
                ->select('jenis_kejadian', 'Pelecehan Seksual')
                ->type('lokasi', 'Gedung B')
                ->type('deskripsi', 'Test status display')
                ->type('phone', '081234567893');

            // Mengubah tipe input ke text agar mudah diisi via Dusk
            $browser->script("document.getElementById('tanggal_kejadian').type = 'text';");
            $browser->type('tanggal_kejadian', '2026-05-03 10:00:00');

            $browser->press('button[type="submit"]')
                    ->waitForText('Kode Tracking Anda adalah', 10)
                    ->assertPathIs('/lapor/sukses');

            $kode = $browser->script('return document.querySelector("span.text-indigo-600").innerText;')[0];

            // 2-3. Track it
            $browser->visit('/track')
                ->type('kode_tracking', $kode)
                ->press('button[type="submit"]');

            // Assert status shown
            $browser->assertSee('Menunggu Verifikasi')
                ->assertSee('Test status display')
                ->screenshot('tc006_valid_status');
        });
    }
}
