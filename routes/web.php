<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/events',[EventController::class, 'index'])->name('events');
    Route::get('/event/{event}', [EventController::class, 'show'])->name('event.show');
    Route::post('/event/{event}/register', [EventController::class, 'register'])->name('event.register');
    Route::delete('/event/{event}/unregister', [EventController::class, 'unregister'])->name('event.unregister');
    Route::get('/events/create', [EventController::class, 'showCreate'])->name('events.create');
    Route::post('/events/create', [EventController::class, 'store'])->name('events.store');
    Route::get('/event/{event}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::put('/event/{event}', [EventController::class, 'update'])->name('event.update');
    Route::post('/event/{event}/cancel', [EventController::class, 'cancel'])->name('event.cancel');

    Route::get('/payment/checkout/{event}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/payment/success/{event}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel/{event}', [PaymentController::class, 'cancel'])->name('payment.cancel');
});

require __DIR__.'/auth.php';
