<?php

namespace Tests\Feature\Todo;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_logged_in_user_should_be_able_to_create_a_todo_item()
    {
        # Arrange
        $user = User::factory()->createOne();
        $assignedTo = User::factory()->createOne();

        $this->actingAs($user);

        # Act
        $response = $this->post(route('todo.store'), [
            'title' => 'Todo Item',
            'description' => 'Todo Item Description',
            'assigned_to' => $assignedTo->id,
        ]);

        # Assert
        $response->assertRedirect(route('todo.index'));

        $this->assertDatabaseHas('todos', [
            'title' => 'Todo Item',
            'description' => 'Todo Item Description',
            'assigned_to_id' => $assignedTo->id,
        ]);
    }

    public function test_a_logged_in_user_should_be_able_to_attach_a_file_to_a_todo_item()
    {
        # Arrange
        Storage::fake('s3');

        $user = User::factory()->createOne();
        $this->actingAs($user);

        # Act
        $this->post(route('todo.store'), [
            'title' => 'Todo Item',
            'description' => 'Todo Item Description',
            'assigned_to' => $user->id,
            'file' => UploadedFile::fake()->image('fake-image.png'),
        ]);

        # Assert
        Storage::disk('s3')->assertExists('todo/fake-image.png');
    }
}
