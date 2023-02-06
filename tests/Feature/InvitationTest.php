<?php

namespace Tests\Feature;

use App\Mail\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    public function test_any_user_should_be_able_to_invite_someone_to_the_platform()
    {
        # Arrange
        Mail::fake();

        $user = User::factory()->create();
        $this->actingAs($user);

        # Act
        $this->post('invite', ['email' => 'new@email.com']);

        # Assert
        Mail::assertSent(Invitation::class, function ($mail) {
            return $mail->hasTo('new@email.com');
        });

        $this->assertDatabaseHas('invites', ['email' => 'new@email.com']);
    }
}
