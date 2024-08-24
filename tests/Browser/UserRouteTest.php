<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserRouteTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function UserTest(): void
    {
        $users = User::select('id')->get();

        $this->browse(function (Browser $browser) use ($users) {
            $browser->visit(route('users.index'))
                ->assertStatus(200);
        });

        $this->browse(function (Browser $browser) use ($users) {
            $browser->visit(route('users.create'))
                ->assertStatus(200);
        });


        foreach ($users as $user) {
            $this->browse(function (Browser $browser) use ($user) {
                $browser->visit(route('users.show', $user->id))
                    ->assertStatus(200);
            });

            $this->browse(function (Browser $browser) use ($user) {
                $browser->visit(route('users.edit', $user->id))
                    ->assertStatus(200);
            });

            $this->browse(function (Browser $browser) use ($user) {
                $browser->visit(route('users.user_roles', $user->id))
                    ->assertStatus(200);
            });
        }
    }
}
