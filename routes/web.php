<?php

use App\Http\Controllers\Member\ArticleController;
use App\Http\Controllers\Member\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\BookmarkController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────
// Area Publik (Guest & Semua User)
// ─────────────────────────────────────────────
Route::get('/', [PostController::class, 'index'])->name('homepage');
Route::get('/category/{category:slug}', [PostController::class, 'category'])->name('category.show');
Route::get('/post/{post:slug}', [PostController::class, 'show'])->name('post.show');

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
    
    // Bookmarks
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/bookmarks/toggle/{post}', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');
});

require __DIR__.'/auth.php';
