<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\MsActionController;
use App\Http\Controllers\MsEmployeeController;

Route::inertia('/', 'Welcome')->name('home');
Route::middleware(['auth:employee'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


Route::get('/about', [\App\Http\Controllers\AboutController::class, 'index'])->name('about');

Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact');
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'sendContact'])->name('contact.send');


Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');


Route::middleware(['auth:employee'])->prefix('ms-action')->name('ms-action.')->group(function () {
    Route::get('/', [MsActionController::class, 'index'])->name('list');
    Route::get('/create', [MsActionController::class, 'create'])->name('create');
    Route::get('/{id}/edit', [MsActionController::class, 'edit'])->name('edit');
    Route::get('/{id}/show', [MsActionController::class, 'show'])->name('show');

    Route::post('/', [MsActionController::class, 'store'])->name('store');
    Route::put('/{id}', [MsActionController::class, 'update'])->name('update');
    Route::delete('/{id}', [MsActionController::class, 'destroy'])->name('destroy');
    Route::post('/bulk-delete', [MsActionController::class, 'destroyBulk'])->name('bulk-destroy');
});

Route::middleware(['auth:employee'])->prefix('ms-employee')->name('ms-employee.')->group(function () {
    Route::get('/', [MsEmployeeController::class, 'index'])->name('list');
   
});
