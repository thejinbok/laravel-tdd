<?php

namespace App\Http\Controllers\Todo;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    public function __invoke()
    {
        Todo::query()
            ->create([
                'title' => request()->title,
                'description' => request()->description,
                'assigned_to_id' => request()->assigned_to,
            ]);

        if (request()->has('file')) {
            request()->file('file')->storeAs(
                'todo',
                request()->file('file')->getClientOriginalName(),
                ['disk' => 's3']
            );
        }

        return redirect()->route('todo.index');
    }
}
