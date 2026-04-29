<?php

use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
Route::middleware(['auth'])->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'index']);
    Route::post('/onboarding/step1', [OnboardingController::class, 'step1']);
    Route::post('/onboarding/step2', [OnboardingController::class, 'step2']);
    Route::post('/onboarding/finish', [OnboardingController::class, 'finish']);
});
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'onboarding'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'plan.limits'])->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
});
Route::middleware(['auth', 'tenant'])->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
});
Route::get('/test', [TestController::class, 'index'])->name('test');

Route::get('/403', function () {
    return Inertia::render('Error403');
})->name('403');

require __DIR__.'/auth.php';
