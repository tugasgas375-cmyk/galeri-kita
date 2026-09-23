<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MomentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MomentController::class, 'index'])->name('home');
Route::get('/galeri', [MomentController::class, 'gallery'])->name('gallery');
Route::get('/momen/{moment}', [MomentController::class, 'show'])->name('moments.show');
Route::post('/momen/{moment}/like', [MomentController::class, 'toggleLike'])->name('moments.like');
Route::get('/momen/{moment}/unduh', [AdminController::class, 'downloadZIP'])->name('moments.download');

Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');

Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/momen/baru', [AdminController::class, 'create'])->name('moments.create');
    Route::post('/momen', [AdminController::class, 'store'])->name('moments.store');
    Route::get('/momen/{moment}/edit', [AdminController::class, 'edit'])->name('moments.edit');
    Route::put('/momen/{moment}', [AdminController::class, 'update'])->name('moments.update');
    Route::delete('/momen/{moment}', [AdminController::class, 'destroy'])->name('moments.destroy');
    Route::get('/momen/{moment}/foto', [AdminController::class, 'photos'])->name('moments.photos');
    Route::post('/momen/{moment}/foto', [AdminController::class, 'storePhotos'])->name('moments.photos.store');
    Route::patch('/foto/{photo}', [AdminController::class, 'updateCaption'])->name('photos.update');
    Route::delete('/foto/{photo}', [AdminController::class, 'destroyPhoto'])->name('photos.destroy');
    Route::post('/foto/{photo}/sampul', [AdminController::class, 'setCover'])->name('photos.cover');
    Route::post('/foto/urut', [AdminController::class, 'reorder'])->name('photos.reorder');
});
