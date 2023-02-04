<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_anyone_should_be_able_to_register_as_a_new_user()
    {
        $response = $this->post(route('register'), [
            'name' => 'Jin Bok',
            'email' => 'jin@quatredeux.com',
            'email_confirmation' => 'jin@quatredeux.com',
            'password' => '3pC9&Fh<G!8M_nzm',
        ]);

        $response->assertRedirect('dashboard');

        $this->assertDatabaseHas('users', [
            'name' => 'Jin Bok',
            'email' => 'jin@quatredeux.com',
        ]);

        $user = User::whereEmail('jin@quatredeux.com')->firstOrFail();

        $this->assertTrue(
            Hash::check('3pC9&Fh<G!8M_nzm', $user->password),
            'Checks if password was saved encrypted.'
        );
    }
}
