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

Auth::routes(['verify' => true]);

Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'showForm'])->name('admin.animal.index');
    Route::post('/dashboard', [App\Http\Controllers\AdminController::class, 'createSpecies'])->name('admin.animal.create');
});

Route::get('/', function () {
    return redirect(app()->getLocale());
});

Route::prefix('{locale}')
->where(['locale' => '[a-zA-Z]{2}'])
->middleware('setlocale')
->group(function () {
    // dd(app()->getLocale());
    Route::get('/', function ($locale) {
        return app()->call('App\Http\Controllers\HomeController@welcome', ['locale' => $locale]);
    })->name('welcome');

    Route::prefix('/species')->group(function () {
        Route::get('/{gbif_id}', [App\Http\Controllers\HomeController::class, 'species'])->name('species');
    });
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
    Route::get('/pets', [App\Http\Controllers\HomeController::class, 'pets'])->name('pets')->middleware('auth');

    Route::prefix('/profile')->middleware('auth')->group(function () {
        Route::get('/', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
        Route::post('/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::post('/delete', [App\Http\Controllers\ProfileController::class, 'delete'])->name('profile.delete');
        Route::post('/upload-image', [App\Http\Controllers\ProfileController::class, 'uploadImage'])->name('profile.upload-image');
        Route::post('/change-password', [App\Http\Controllers\ProfileController::class, 'changePassword'])->name('profile.change-password');
        // Customize the email verification route
        Route::get('/verify-email/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])
        ->middleware(['auth', 'signed', 'throttle:6,1'])
        ->name('profile.verify-email');
    });
    Route::prefix('/popular')->group(function () {
        Route::get('/{slug}', [App\Http\Controllers\HomeController::class, 'popular'])->name('popular');
    });
    // Route::prefix('/{livestock}')->group(function () {
    Route::prefix('/livestock')->group(function () {
        Route::get('/', [App\Http\Controllers\HomeController::class, 'livestock'])->name('livestock');
        // Route::get('/{slug}', [App\Http\Controllers\HomeController::class, 'livestockAnimal'])->name('livestock.animal');
    });
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