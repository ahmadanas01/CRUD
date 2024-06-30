<?php

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\EmployeeApiController;

Route::get('/employees', [EmployeeApiController::class, 'index']);
Route::post('/employees', [EmployeeApiController::class, 'store']);
Route::get('/employees/{id}', [EmployeeApiController::class, 'edit']);
Route::put('/employees/{id}', [EmployeeApiController::class, 'update']);
Route::delete('/employees/{id}', [EmployeeApiController::class, 'destroy']);





