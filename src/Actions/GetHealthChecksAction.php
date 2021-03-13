<?php

namespace Vinnygambiny\LaravelMonitoring\Actions;

use Illuminate\Support\Facades\Http;

class GetHealthChecksAction
{
    public function execute()
    {
        if (!config('monitoring.healthchecks.key')) {
            return [];
        }

        return Http::withHeaders([
            'X-Api-Key' => config('monitoring.healthchecks.key'),
        ])->get('https://healthchecks.io/api/v1/checks/');
    }
}
