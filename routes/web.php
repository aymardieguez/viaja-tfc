<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ViajeController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/mis-viajes', [ViajeController::class, 'index'])->name('viajes.index');
    Route::get('/mis-viajes/crear', [ViajeController::class, 'create'])->name('viajes.create');
    Route::post('/mis-viajes', [ViajeController::class, 'store'])->name('viajes.store');
    Route::get('/mis-viajes/{viaje}', [ViajeController::class, 'show'])->name('viajes.show');
});

require __DIR__.'/auth.php';
