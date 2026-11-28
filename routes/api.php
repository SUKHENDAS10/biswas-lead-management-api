<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminLeadController;

// Public Lead Form API
Route::post('/leads', [LeadController::class, 'store'])
    ->middleware('throttle:10,1');

// Public Login API
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1');

// Protected Admin APIs
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {

    // Get all leads or filter by status
    Route::get('/leads', [AdminLeadController::class, 'index']);

    // Update lead status
    Route::patch('/leads/{id}/status', [AdminLeadController::class, 'updateStatus']);

});

// Protected Logout API
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');