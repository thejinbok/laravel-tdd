<?php

namespace Tests\Feature\Todo;

use App\Models\Todo;
use App\Models\User;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    public function test_a_logged_in_user_should_be_able_to_delete_a_todo_item()
    {
        $user = User::factory()->createOne();
        $todo = Todo::factory()->createOne(['assigned_to_id' => $user->id]);

        $this->actingAs($user);

        $this->delete(route('todo.destroy', $todo))->assertRedirect(route('todo.index'));

        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id,
        ]);
    }

    public function test_only_the_assigned_user_should_be_able_to_delete_a_todo_item()
    {
        $user1 = User::factory()->createOne();
        $user1Todo = Todo::factory()->createOne(['assigned_to_id' => $user1->id]);

        $user2 = User::factory()->createOne();

        $this->actingAs($user2);

        $this->delete(route('todo.destroy', $user1Todo))->assertForbidden();

        $this->assertDatabaseHas('todos', [
            'id' => $user1Todo->id,
        ]);

        $this->actingAs($user1);

        $this->delete(route('todo.destroy', $user1Todo))->assertRedirect(route('todo.index'));

        $this->assertDatabaseMissing('todos', [
            'id' => $user1Todo->id,
        ]);
    }
}
