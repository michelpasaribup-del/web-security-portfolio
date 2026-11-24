<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScannerController;
use Illuminate\Support\Facades\Route;

// Halaman depan langsung diarahkan ke dashboard scanner
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Halaman Dashboard (Hanya bisa diakses jika sudah login)
Route::get('/dashboard', [ScannerController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Proses Scan URL
Route::post('/scan', [ScannerController::class, 'scan'])
    ->middleware(['auth'])
    ->name('scan.process');

// Route bawaan Breeze (untuk profile)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
