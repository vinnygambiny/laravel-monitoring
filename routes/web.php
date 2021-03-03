<?php

use Vinnygambiny\LaravelMonitoring\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)
    ->name('monitoring.index');
