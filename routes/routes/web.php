<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    PatientController,
    DoctorController,
    AppointmentController,
    BillingController,
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Patients
    Route::resource('patients', PatientController::class);

    // Doctors
    Route::resource('doctors', DoctorController::class);

    // Appointments
    Route::resource('appointments', AppointmentController::class);
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])
         ->name('appointments.update-status');

    // Billing
    Route::resource('billing', BillingController::class)->except(['edit', 'update']);
    Route::post('/billing/{billing}/payment', [BillingController::class, 'recordPayment'])
         ->name('billing.record-payment');

});

require __DIR__ . '/auth.php';
