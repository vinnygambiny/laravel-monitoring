<?php

namespace Vinnygambiny\LaravelMonitoring\Http\Controllers\API;

use Vinnygambiny\LaravelMonitoring\Actions\GenerateAwsGraphAction;
use Vinnygambiny\LaravelMonitoring\Http\Controllers\Controller;

class CloudWatchGraphController extends Controller
{
    public function __invoke(GenerateAwsGraphAction $generateAwsGraphAction)
    {
        return $generateAwsGraphAction->execute();
    }
}
