<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LeadController;

// Task 1: Lead Form API
Route::post('/leads', [LeadController::class, 'store'])->middleware('throttle:10,1');