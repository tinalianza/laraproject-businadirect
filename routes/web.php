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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisteredVehiclesController;

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

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

Route::post('/update-profile-image', [UserController::class, 'updateProfileImage'])->name('update.profile.image');
// Routes for registered vehicles

// Route for listing registered vehicles with pagination
Route::get('/registered-vehicles', [VehicleController::class, 'index'])->name('registered-vehicles.index');

// Route for showing details of a specific vehicle owner
Route::get('/vehicle-owner/{id}', [VehicleController::class, 'showDetails'])->name('vehicle-owner.details');

Route::get('/vehicle/{id}', [VehicleController::class, 'showDetails'])->name('reg_details');

Route::get('/vehicle/renew/{id}', [VehicleController::class, 'renew'])->name('vehicle.renew');
Route::post('/vehicle/add', [RegistrationController::class, 'store'])->name('vehicle.add');