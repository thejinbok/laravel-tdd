<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
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

    public function test_name_parameter_is_required()
    {
        $this->post(route('register'), [])
            ->assertSessionHasErrors([
                'name' => __('validation.required', ['attribute' => 'name']),
            ]);
    }

    public function test_name_parameter_max_length_should_be_255()
    {
        $this->post(route('register'), [
            'name' => str_repeat('X', 256),
        ])->assertSessionHasErrors([
            'name' => __('validation.max.string', ['attribute' => 'name', 'max' => 255]),
        ]);
    }

    public function test_email_parameter_is_required()
    {
        $this->post(route('register'), [])
            ->assertSessionHasErrors([
                'email' => __('validation.required', ['attribute' => 'email']),
            ]);
    }

    public function test_email_parameter_should_be_valid()
    {
        $this->post(route('register'), [
            'email' => 'invalid-email',
        ])->assertSessionHasErrors([
            'email' => __('validation.email', ['attribute' => 'email']),
        ]);
    }

    public function test_email_parameter_should_be_unique()
    {
        # Arrange
        User::factory()->create(['email' => 'jin@quatredeux.com']);

        # Act
        $this->post(route('register'), [
            'email' => 'jin@quatredeux.com',
        ])->assertSessionHasErrors([ # Assert
            'email' => __('validation.unique', ['attribute' => 'email']),
        ]);
    }

    public function test_email_parameter_should_be_confirmed()
    {
        $this->post(route('register'), [
            'email' => 'jin@quatredeux.com',
            'email_confirmation' => '',
        ])->assertSessionHasErrors([
            'email' => __('validation.confirmed', ['attribute' => 'email']),
        ]);
    }

    public function test_password_parameter_is_required()
    {
        $this->post(route('register'), [])
            ->assertSessionHasErrors([
                'password' => __('validation.required', ['attribute' => 'password']),
            ]);
    }
}
