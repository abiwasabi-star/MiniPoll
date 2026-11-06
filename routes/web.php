<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ProfileController;


//public page
Route::get('/', function () {
    return Inertia::render('Home');
});

//public routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

//private routes
Route::middleware('auth')->group(function () {
    Route::get('/vote', [VotingController::class, 'index'])->name('vote');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

//admin routes //someone shud b logged in n an admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::post('/skaters', [AdminController::class, 'store'])->name('skaters.store');
        Route::put('/skaters/{skater}', [AdminController::class, 'update'])->name('skaters.update');
        Route::delete('/skaters/{skater}', [AdminController::class, 'destroy'])->name('skaters.destroy');
        Route::get('/contestants', [AdminController::class, 'contestants'])->name('contestants');
        Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
        
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');
    });
    