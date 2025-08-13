<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\MinuteController;
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->middleware('throttle:5,5')->name('authenticate');
});

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('throttle:5,5')->name('logout');

    // Default URL
    Route::get('/', fn() => redirect()->route('dashboard'));

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notulen
    Route::get('/dashboard/notulen', [MinuteController::class, 'index'])->name('dashboard.minute.index');
    Route::get('/dashboard/notulen/tambah', [MinuteController::class, 'create'])->name('dashboard.minute.create');
    Route::post('/dashboard/notulen/tambah', [MinuteController::class, 'store'])->name('dashboard.minute.store');
    Route::get('/dashboard/notulen/{slug}/cetak', [MinuteController::class, 'printPDF'])->name('dashboard.minute.printPDF');
    Route::get('/dashboard/notulen/{slug}/ubah', [MinuteController::class, 'edit'])->name('dashboard.minute.edit');
    Route::put('/dashboard/notulen/{slug}/ubah', [MinuteController::class, 'update'])->name('dashboard.minute.update');
    Route::delete('/dashboard/notulen/{slug}/hapus', [MinuteController::class, 'destroy'])->name('dashboard.minute.destroy');

    // Notulen
    Route::get('/dashboard/pengguna', [UserController::class, 'index'])->name('dashboard.user.index');
    Route::get('/dashboard/pengguna/tambah', [UserController::class, 'create'])->name('dashboard.user.create');
    Route::post('/dashboard/pengguna/tambah', [UserController::class, 'store'])->name('dashboard.user.store');
    Route::get('/dashboard/pengguna/{id}/ubah', [UserController::class, 'edit'])->name('dashboard.user.edit');
    Route::put('/dashboard/pengguna/{id}/ubah', [UserController::class, 'update'])->name('dashboard.user.update');
    Route::delete('/dashboard/pengguna/{id}/hapus', [UserController::class, 'destroy'])->name('dashboard.user.destroy');
});
