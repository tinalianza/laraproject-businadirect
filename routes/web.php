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
use App\Http\Controllers\RenewController;
use App\Http\Controllers\AddVehicleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ForgotPasswordController;


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

// // Route for listing registered vehicles with pagination
// Route::get('/registered-vehicles', [VehicleController::class, 'index'])->name('registered-vehicles.index');

// // Route for showing details of a specific vehicle owner
Route::get('/vehicle-owner/{id}', [VehicleController::class, 'showDetails'])->name('vehicle-owner.details');

Route::get('/vehicle/{id}', [VehicleController::class, 'showDetails'])->name('reg_details');

Route::post('/vehicle/add', [RegistrationController::class, 'store'])->name('vehicle.add');

Route::get('/addvehicle', [RegistrationController::class, 'showAddVehicleForm'])->name('add.vehicle');
Route::post('/addvehicle', [RegistrationController::class, 'addVehicle'])->name('add.vehicle.store');
Route::post('/vehicle/{id}/edit', [RegistrationController::class, 'editVehicle'])->name('vehicle.edit');
Route::post('/vehicle/{id}/update', [RegistrationController::class, 'updateVehicle'])->name('vehicle.update');

// In routes/web.php
Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

Route::get('/payment-success', [VehicleController::class, 'paymentSuccess'])->name('payment-success');
Route::get('/payment-failed', [VehicleController::class, 'paymentFailed'])->name('payment-failed');

Route::get('/vehicles/{vehicle_id}/renew', [AddVehicleController::class, 'showAddVehicleForm'])
    ->name('vehicles.renew.form');

// Route to handle the vehicle renewal process
Route::post('/vehicles/{vehicle_id}/renew', [AddVehicleController::class, 'addvehicle'])
    ->name('vehicles.renew.submit');

// Route to handle payment success
Route::get('/vehicles/{vehicle_id}/payment-success', [RenewController::class, 'paymentSuccess'])
    ->name('payment-success');

// Route to show the renewal form for a specific vehicle
Route::get('/renew/{vehicle_id}', [RenewController::class, 'showRenewForm'])
    ->middleware('auth') // Ensure only authenticated users can access this
    ->name('vehicle.renew.form');


// // Route to handle the renewal submission for a specific vehicle
// Route::post('/renew/{vehicle_id}', [RenewController::class, 'renew'])
//     ->middleware('auth')
//     ->name('renew.submit');
Route::post('/renew', [RenewController::class, 'store'])->name('renew.submit');
// Route to show the add vehicle form for a specific vehicle
Route::get('/addvehicle/{vehicle_id}', [AddVehicleController::class, 'showAddVehicleForm'])
    ->middleware('auth')
    ->name('vehicle.addvehicle.form');

// Route to handle the vehicle addition/renewal submission for a specific vehicle
// Route::post('/addvehicle/{vehicle_id}', [AddVehicleController::class, 'addvehicle'])
//     ->middleware('auth')
//     ->name('addvehicle.submit');

    Route::post('/addvehicle', [AddVehicleController::class, 'store'])->name('addvehicle.submit');
    Route::get('/edit-profile', [ProfileController::class, 'showEditForm'])->name('edit.page');
    Route::put('/profile-update', [ProfileController::class, 'updateProfile'])->name('profile.update');

    Route::get('/new-application-confirmation', [ApplicationController::class, 'showConfirmation'])
    ->name('new-application-confirmation');

// Display the login form
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');

// Handle the login request
Route::post('/login', [LoginController::class, 'login'])->name('login');

// Handle user logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('forgot_pass', [ForgotPasswordController::class, 'showForm'])->name('forgot_pass');


// Route to handle the submission of the forgot password form
Route::post('/forgot_pass', [ForgotPasswordController::class, 'sendResetCode'])->name('password.email');

// Route for the password reset form
Route::get('/reset_password/{emp_no}/{t}', [ForgotPasswordController::class, 'showResetForm'])->name('reset_new_pass');

// Route to handle the password reset submission
Route::post('/reset_password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

Route::get('reset_password/{email}/{t}', [ForgotPasswordController::class, 'resetPassword'])->name('reset_new_pass');

Route::post('/password/send-reset-code', [ForgotPasswordController::class, 'sendResetCode'])->name('password.sendResetCode');


Route::get('password/request', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('password/send', [ForgotPasswordController::class, 'sendResetCode'])->name('password.send');
Route::get('password/reset', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/password/verify', [ForgotPasswordController::class, 'verifyResetCode'])->name('password.verify');

