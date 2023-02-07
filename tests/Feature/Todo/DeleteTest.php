<?php

namespace Tests\Feature\Todo;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    public function test_a_logged_in_user_should_be_able_to_delete_a_todo_item()
    {
        $user = User::factory()->createOne();
        $todo = Todo::factory()->createOne();

        $this->actingAs($user);

        $this->delete(route('todo.destroy', $todo))->assertRedirect(route('todo.index'));

        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id,
        ]);
    }
}
