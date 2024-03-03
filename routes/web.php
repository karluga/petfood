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
        // dd(app()->getLocale());
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');
 
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware(['auth'])->name('home');
    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
    Route::get('/pets', [App\Http\Controllers\HomeController::class, 'pets'])->name('pets')->middleware('auth');

    Route::prefix('/profile')->middleware('auth')->group(function () {
        Route::get('/', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
        Route::post('/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::post('/upload-image', [App\Http\Controllers\ProfileController::class, 'uploadImage'])->name('profile.upload-image');
        Route::get('/change-password', [App\Http\Controllers\ProfileController::class, 'changePassword'])->name('profile.change-password');
    });
    Route::get('/pets', [App\Http\Controllers\HomeController::class, 'pets'])->middleware(['auth'])->name('pets');
 
});

// Facebook Login URL
Route::prefix('facebook')->name('facebook.')->group( function(){
    Route::get('auth', [App\Http\Controllers\Auth\FaceBookController::class, 'loginUsingFacebook'])->name('login');
    Route::get('callback', [App\Http\Controllers\Auth\FaceBookController::class, 'callbackFromFacebook'])->name('callback');
});

// Google URL
Route::prefix('google')->name('google.')->group( function(){
    Route::get('login', [App\Http\Controllers\Auth\GoogleController::class, 'loginWithGoogle'])->name('login');
    Route::any('callback', [App\Http\Controllers\Auth\GoogleController::class, 'callbackFromGoogle'])->name('callback');
});

// Route::prefix('user')->middleware(['auth'])->group(function () {
//     Route::get('/profile', function () {
//         return 'Hello';
//     });
// });