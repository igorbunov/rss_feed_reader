<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\FeedResultController;

Auth::routes(['verify' => true]);

Route::group([
        'middleware' => ['auth', 'verified']
    ], function () {
    Route::get('/', [FeedResultController::class, 'index'])->name('home');

    Route::resource('feed', FeedController::class);
});