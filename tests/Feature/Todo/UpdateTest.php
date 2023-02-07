<?php

namespace Tests\Feature\Todo;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_logged_in_user_should_be_able_to_update_a_todo_item()
    {
        $user = User::factory()->createOne();
        $todo = Todo::factory()->createOne();

        $this->actingAs($user);

        $this->put(route('todo.update', $todo), [
            'title' => 'Updated Todo Item',
            'description' => 'Updated Todo Item Description',
            'assigned_to' => $user->id,
        ])->assertRedirect(route('todo.index'));

        $todo->refresh();

        $this->assertEquals('Updated Todo Item', $todo->title);
        $this->assertEquals('Updated Todo Item Description', $todo->description);
    }
}
