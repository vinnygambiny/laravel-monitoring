<?php

namespace Vinnygambiny\LaravelMonitoring\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Home', [
            'hasHorizon' => Route::has('horizon.stats.index'),
            'hasAws' => config('monitoring.aws.cloudwatch') &&
                config('monitoring.aws.config.credentials.key') &&
                config('monitoring.aws.config.credentials.secret') &&
                config('monitoring.aws.config.region'),
            'statusPages' => config('monitoring.status_pages', []),
            'thresholdMaxWaitTime' => config('monitoring.threshold_max_wait_time', 300),
        ]);
    }
}
