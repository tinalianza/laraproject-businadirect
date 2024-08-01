<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApplicationPendingController;
use App\Http\Controllers\PaymentSuccessController;
use App\Http\Controllers\PaymentFailedController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EditController;
use App\Http\Controllers\ViolationController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Middleware\CheckRegistration;

Route::get('/', function () {
    return view('home');
});

Route::get('/account', function () {
    return view('account');
});

Route::get('/view', function () {
    return view('view');
});

Route::get('/faq', function () {
    return view('faq');
});

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
});

Route::get('/guidelines', function () {
    return view('guidelines');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegistrationController::class, 'store'])->name('register.submit');

Route::middleware([CheckRegistration::class])->group(function () {
    Route::get('/application-confirmation', [ApplicationController::class, 'showConfirmation'])->name('application-confirmation');
    Route::post('/application-confirmation', [ApplicationController::class, 'submitConfirmation'])->name('application-confirmation.submit');
});

Route::post('/create-payment-link', [PaymentController::class, 'createPayment'])->name('application.createPayment');
Route::get('/payment-success', [PaymentSuccessController::class, 'index']);
Route::get('/payment-failed', [PaymentFailedController::class, 'index'])->name('payment-failed');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/application-pending', [ApplicationPendingController::class, 'doneApplication'])->name('application-pending');

Route::get('/vehiclelist', [VehicleController::class, 'index'])->name('vehicles.list');
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.list');
Route::get('/edit', [EditController::class, 'index'])->name('edit.page');
Route::get('/alerts', [ViolationController::class, 'index'])->name('violation.page');
