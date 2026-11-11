<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PickupController;
use App\Http\Controllers\SawController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\WasteRecordController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin dan petugas
    Route::resource('records', WasteRecordController::class)->except('show')->parameters(['records' => 'record']);
    Route::resource('pickups', PickupController::class)->except('show')->parameters(['pickups' => 'pickup']);
    Route::get('/saw', [SawController::class, 'index'])->name('saw.index');
    Route::post('/saw/simpan', [SawController::class, 'simpan'])->name('saw.simpan');

    // Khusus admin
    Route::middleware(RoleMiddleware::class . ':admin')->group(function () {
        Route::resource('locations', LocationController::class)->except('show')->parameters(['locations' => 'location']);
        Route::resource('vehicles', VehicleController::class)->except('show')->parameters(['vehicles' => 'vehicle']);
        Route::resource('criterias', CriteriaController::class)->except('show')->parameters(['criterias' => 'criteria']);
    });
});
