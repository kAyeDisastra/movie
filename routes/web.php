<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
Route::get('/films/{id}', [\App\Http\Controllers\DashboardController::class, 'show'])->name('films.show');
Route::get('/films/{id}/schedules', [\App\Http\Controllers\DashboardController::class, 'schedules'])->name('films.schedules');

Route::controller(\App\Http\Controllers\AuthController::class)->group(function () {
    Route::get('/login', 'indexLogin')->name('login');
    Route::post('/login', 'login')->name('login.post');
    Route::get('/register', 'indexRegister')->name('register');
    Route::post('/register', 'register')->name('register.post');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
});

Route::post('/admin/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('filament.admin.auth.logout');

Route::post('/owner/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('filament.owner.auth.logout');

Route::get('/upcoming-movies', [\App\Http\Controllers\UpcomingMovieController::class, 'index'])->name('upcoming.movies');
Route::middleware('auth')->group(function () {
    Route::get('/studios', [\App\Http\Controllers\StudioController::class, 'index'])->name('studios');
    Route::get('/studios/{id}', [\App\Http\Controllers\StudioController::class, 'show'])->name('studios.show');
    Route::post('/booking', [\App\Http\Controllers\BookingController::class, 'store'])->name('booking.store');
    Route::post('/booking/confirm', [\App\Http\Controllers\BookingController::class, 'confirmPayment'])->name('booking.confirm');
    Route::post('/payment/create', [\App\Http\Controllers\PaymentController::class, 'createPayment'])->name('payment.create');
    Route::get('/transactions', [\App\Http\Controllers\TransactionController::class, 'index'])->name('transactions');
});

Route::post('/payment/callback', [\App\Http\Controllers\PaymentController::class, 'callback'])->name('payment.callback');

Route::prefix('movies')->middleware('auth')->group(function () {
    Route::controller(\App\Http\Controllers\Movie\MovieDashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('movies.dashboard');
    });
});
