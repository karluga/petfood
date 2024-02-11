<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
    // Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});
Route::prefix('user')->middleware(['auth'])->group(function () {
    Route::get('/profile', function () {
        return 'Hello';
    });
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile');
Route::get('/pets', [App\Http\Controllers\HomeController::class, 'pets'])->name('pets');