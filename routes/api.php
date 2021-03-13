<?php

use Illuminate\Support\Facades\Route;
use Vinnygambiny\LaravelMonitoring\Http\Controllers\API\CloudWatchAlarmsController;
use Vinnygambiny\LaravelMonitoring\Http\Controllers\API\CloudWatchGraphController;
use Vinnygambiny\LaravelMonitoring\Http\Controllers\API\HealthChecksController;


Route::get('/cloudwatch/alarms', CloudWatchAlarmsController::class)
    ->name('monitoring.api.cloudwatch.alarms');

Route::get('/cloudwatch/graph', CloudWatchGraphController::class)
    ->name('monitoring.api.cloudwatch.graph');

Route::get('/healthcheks', HealthChecksController::class)
    ->name('monitoring.api.healthchecks');
