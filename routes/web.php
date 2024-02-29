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

Auth::routes();

Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
    // Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});
Route::get('/', function () {
    return redirect(app()->getLocale());
});
Route::get('/change-language', function () {
    $selectedLanguage = request('language');
    return redirect("/$selectedLanguage");
})->name('change.language');

Route::prefix('{locale}')
    ->where(['locale' => '[a-zA-Z]{2}'])
    ->middleware('setlocale')
    ->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
 
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware(['auth'])->name('home');
    Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profile'])->middleware(['auth'])->name('profile');
    Route::get('/pets', [App\Http\Controllers\HomeController::class, 'pets'])->middleware(['auth'])->name('pets');
 
});


// Route::prefix('user')->middleware(['auth'])->group(function () {
//     Route::get('/profile', function () {
//         return 'Hello';
//     });
// });