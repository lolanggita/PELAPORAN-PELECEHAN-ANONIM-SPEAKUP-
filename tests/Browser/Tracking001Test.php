<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class Tracking001Test extends DuskTestCase
{
    /**
     * TC.Tracking.001: Generate kode tracking otomatis saat submit laporan valid (Positive)
     */
    public function test_tracking001(): void
    {
        $this->browse(function (Browser $browser) {
            // 1. Launch
            $browser->visit('/lapor')
                    ->select('jenis_kejadian', 'Pelecehan Seksual')
                    ->type('lokasi', 'Kampus Utama')
                    ->type('deskripsi', 'Description lengkap untuk test generate kode tracking')
                    ->type('phone', '081234567890');
            
            $browser->script("document.getElementById('tanggal_kejadian').type = 'text';");
            $browser->type('tanggal_kejadian', '2026-05-03 10:00:00');
            
            $browser->press('button[type="submit"]')
                    ->waitForText('Kode Tracking Anda adalah', 10)
                    ->assertPathIs('/lapor/sukses')
                    ->assertSee('Laporan Berhasil Dikirim!')
                    ->assertSee('Kode Tracking Anda adalah:')
                    ->assertSee('SU-');
        });
    }
}
