<?php

use App\Http\Controllers\Member\ArticleController;
use App\Http\Controllers\Member\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────
// Area Publik (Guest & Semua User)
// ─────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ─────────────────────────────────────────────
// Area Member / Kontributor (Harus Login)
// ─────────────────────────────────────────────
Route::prefix('member')
    ->name('member.')
    ->middleware(['auth', 'role:contributor|user|reporter|editor|admin|super_admin'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Artikel Kontributor
        Route::get('/artikel', [ArticleController::class, 'index'])
            ->name('articles.index');
        Route::get('/artikel/tulis', [ArticleController::class, 'create'])
            ->name('articles.create');
        Route::post('/artikel', [ArticleController::class, 'store'])
            ->name('articles.store');
    });

// Redirect rute /dashboard bawaan Breeze ke member dashboard
Route::get('/dashboard', function () {
    return redirect()->route('member.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
