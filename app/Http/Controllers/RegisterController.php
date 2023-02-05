<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function __invoke()
    {
        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users', 'confirmed'],
            'password' => ['required', Password::defaults()],
        ]);

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
