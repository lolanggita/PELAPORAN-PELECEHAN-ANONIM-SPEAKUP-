<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WelcomePageTest extends DuskTestCase
{
    /**
     * Test the Anonymous Report button in the hero section.
     */
    public function testLaporAnonimButton(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->clickLink('Lapor Sekarang (Anonim)')
                    ->assertPathIs('/lapor');
        });
    }

    /**
     * Test the Check Status button in the hero section.
     */
    public function testCekStatusHeroButton(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->clickLink('Cek Status Laporan')
                    ->assertPathIs('/track');
        });
    }

    /**
     * Test the Check Status button in the header.
     */
    public function testCekStatusHeaderButton(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->clickLink('Cek Status')
                    ->assertPathIs('/track');
        });
    }

    /**
     * Test the Admin login button in the header.
     */
    public function testAdminLoginButton(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->clickLink('Admin')
                    ->assertPathIs('/admin/login');
        });
    }

    /**
     * Test the smooth scrolling functionality.
     */
    public function testPageScrolling(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->script('window.scrollTo(0, 800);');
            
            $browser->pause(500)
                    ->assertSee('100% Anonim');

            $browser->script('window.scrollTo(0, 0);');
            
            $browser->pause(500)
                    ->assertVisible('h1');
        });
    }

    /**
     * Test the Customer Service chat widget.
     */
    public function testChatWidget(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertMissing('#chatWidget')
                    ->click('#chatToggle')
                    ->waitFor('#chatWidget')
                    ->assertVisible('#chatWidget')
                    ->click('#closeChat')
                    ->assertMissing('#chatWidget');
        });
    }
}
