<?php

namespace App\Http\Controllers;

use App\Models\User;

class RegisterController extends Controller
{
    public function __invoke()
    {
        $user = User::query()->create([
            'name' => request()->name,
            'email' => request()->email,
            'email_confirmation' => request()->email_confirmation,
            'password' => bcrypt(request()->password),
        ]);

        auth()->login($user);

        return redirect('dashboard');
    }
}
