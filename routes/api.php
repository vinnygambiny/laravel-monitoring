<?php

use Vinnygambiny\LaravelMonitoring\Http\Controllers\API\CloudWatchAlarmsController;
use Vinnygambiny\LaravelMonitoring\Http\Controllers\API\CloudWatchGraphController;
use Illuminate\Support\Facades\Route;


Route::get('/cloudwatch/alarms', CloudWatchAlarmsController::class)
    ->name('monitoring.api.cloudwatch.alarms');

Route::get('/cloudwatch/graph', CloudWatchGraphController::class)
    ->name('monitoring.api.cloudwatch.graph');
