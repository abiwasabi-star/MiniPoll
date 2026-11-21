<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordResetController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;


//public page
Route::get('/', function () {
    return Inertia::render('Home');
});

Route::get('/session-check', function () {
    return response()->noContent();
});

//public routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/forgot-password', [PasswordResetController::class, 'create'])
    ->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])
    ->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])
    ->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])
    ->name('password.store');
});

// Email verification routes
Route::middleware('auth')->group(function () {

    // Display verification notice
    Route::get('/email/verify', function () {return Inertia::render('Auth/VerifyEmail');
    })->name('verification.notice');

    // Handle verification link
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('home');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    // Resend verification email
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware('throttle:6,1')->name('verification.send');

});

// Logout - authenticated users, verified or not
Route::middleware(['auth', \App\Http\Middleware\PreventBackHistory::class])
    ->post('/logout', [LoginController::class, 'destroy'])->name('logout');

//private routes authenticated  ANd verified users
Route::middleware(['auth','verified', \App\Http\Middleware\PreventBackHistory::class,])->group(function () {
    Route::get('/home', [VotingController::class, 'index'])->name('home');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/vote', [VotingController::class, 'store'])->name('vote.store');
    Route::get('/skaters/{skater}', [VotingController::class, 'show'])->name('skaters.show');
});

//admin routes //someone shud b logged in n an admin
Route::middleware(['auth','verified', 'admin',\App\Http\Middleware\PreventBackHistory::class,])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::post('/skaters', [AdminController::class, 'store'])->name('skaters.store');
        Route::put('/skaters/{skater}', [AdminController::class, 'update'])->name('skaters.update');
        Route::delete('/skaters/{skater}', [AdminController::class, 'destroy'])->name('skaters.destroy');
        Route::get('/contestants', [AdminController::class, 'contestants'])->name('contestants');
        Route::get('/categories', [AdminController::class, 'categories'])->name('categories');

        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');
        Route::put('/voting-settings', [AdminController::class, 'updateVotingSettings'])->name('voting-settings.update');
    });
    