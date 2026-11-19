<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{

    use DatabaseMigrations;

    /**
     * A Dusk test example.
     */
    public function testLogin(): void
    {
        $roles = ['Super Admin', 'User'];

        foreach ($roles as $role) {
            \Spatie\Permission\Models\Role::create([
                'name' => $role,
            ]);
        }

        $user = User::factory()->create(['email' => 'm@i.com', 'name' => 'mim']);
        $user->assignRole('Super Admin');
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'm@i.com')
                ->type('password', 'password')
                ->press('login')
                ->waitForLocation('/dashboard')
                ->assertPathIs('/dashboard')
                ->screenshot('login');
        });
    }
}
