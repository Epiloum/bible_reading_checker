<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\ReadingController;

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

// Index
Route::get('/', [GeneralController::class, 'index'])->name('index');

// Social Login
Route::get('social/req', [GeneralController::class, 'reqLogin'])->name('social.request');

Route::get('social/{provider}', [
    'as' => 'social.login',
    'uses' => 'App\Http\Controllers\Auth\SocialController@execute',
]);

Route::redirect('home', 'app/reading');

// App
/*
Route::middleware('auth')->prefix('app')->group(function () {
    // Entrance
    Route::redirect('/', 'app/reading');

    // Bible Reading Table
    Route::get('reading', [ReadingController::class, 'index']);
});
*/
Route::prefix('app')->group(function () {
    // Entrance
    Route::redirect('/', 'app/reading');

    // Bible Reading Table
    Route::get('reading', [ReadingController::class, 'index']);
});
