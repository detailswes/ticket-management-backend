<?php

use Illuminate\Support\Facades\Route;

Route::get('/{catchall?}', function () {
    return response()->view('welcome');
})->where('catchall', '(.*)');
