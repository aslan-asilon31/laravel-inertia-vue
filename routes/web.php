<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('/products', \App\Http\Controllers\ProductController::class);

    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
