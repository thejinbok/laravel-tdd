<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('register', function () {
    User::query()->create([
        'name' => request()->name,
        'email' => request()->email,
        'email_confirmation' => request()->email_confirmation,
        'password' => bcrypt(request()->password),
    ]);

    return redirect('dashboard');
})->name('register');
