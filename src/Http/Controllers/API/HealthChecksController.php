<?php

namespace Vinnygambiny\LaravelMonitoring\Http\Controllers\API;

use Vinnygambiny\LaravelMonitoring\Actions\GetHealthChecksAction;
use Vinnygambiny\LaravelMonitoring\Http\Controllers\Controller;

class HealthChecksController extends Controller
{
    public function __invoke(GetHealthChecksAction $getHealthChecks)
    {
        return $getHealthChecks->execute();
    }
}
