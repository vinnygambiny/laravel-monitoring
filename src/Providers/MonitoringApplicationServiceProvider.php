<?php

namespace Vinnygambiny\LaravelMonitoring\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class MonitoringApplicationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->gate();
    }

    protected function gate()
    {
        Gate::define('viewMonitoring', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    public function register()
    {
        //
    }
}
