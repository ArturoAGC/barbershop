<?php

use App\Http\Controllers\Admin\BarberController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Client\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rutas del panel de administración
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('services', ServiceController::class);
    Route::resource('barbers', BarberController::class);
    Route::resource('reservations', ReservationController::class);
});

// Rutas del portal del cliente
Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', function () {
        return view('client.dashboard');
    })->name('dashboard');

    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/my-reservations', [BookingController::class, 'myReservations'])->name('reservations');
    Route::delete('/my-reservations/{reservation}', [BookingController::class, 'cancel'])->name('reservations.cancel');
});
