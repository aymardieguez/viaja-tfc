<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ViajeController;
use App\Http\Controllers\AdminController;
use App\Models\Viaje;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        // Buscamos los viajes del usuario logueado, ordenados por el más reciente
        'viajes' => Viaje::where('user_id', Auth::id())->latest()->get()
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/estado-verificacion', function (\Illuminate\Http\Request $request) {
        return response()->json(['verificado' => $request->user()->hasVerifiedEmail()]);
    })->name('verificacion.estado');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/mis-viajes/crear', [ViajeController::class, 'create'])->name('viajes.create');
    Route::post('/mis-viajes', [ViajeController::class, 'store'])->name('viajes.store');
    Route::get('/mis-viajes/{viaje}', [ViajeController::class, 'show'])->name('viajes.show');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/usuarios/{user}/viajes', [AdminController::class, 'usuarioViajes'])->name('usuarios.viajes');
    Route::delete('/usuarios/{user}', [AdminController::class, 'destroyUser'])->name('usuarios.destroy');
    Route::delete('/viajes/{viaje}', [AdminController::class, 'destroyViaje'])->name('viajes.destroy');
});

require __DIR__ . '/auth.php';
