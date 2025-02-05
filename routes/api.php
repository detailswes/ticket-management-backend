<?php

use App\Http\Controllers\TicketController;
use App\Http\Controllers\TokenController;
use App\Http\Middleware\EnsureTokenIsValid;
use Illuminate\Support\Facades\Route;

Route::post('generate-token', [TokenController::class, 'generateToken']);

Route::middleware(EnsureTokenIsValid::class)->group(function () {
    Route::apiResource('tickets', TicketController::class);
});



