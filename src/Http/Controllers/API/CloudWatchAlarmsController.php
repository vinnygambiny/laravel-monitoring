<?php

namespace Vinnygambiny\LaravelMonitoring\Http\Controllers\API;

use Vinnygambiny\LaravelMonitoring\Actions\GenerateAwsMetricAlarmsAction;
use Vinnygambiny\LaravelMonitoring\Http\Controllers\Controller;

class CloudWatchAlarmsController extends Controller
{
    public function __invoke(GenerateAwsMetricAlarmsAction $generateMetricAlarmsAction)
    {
        return $generateMetricAlarmsAction->execute();
    }
}
