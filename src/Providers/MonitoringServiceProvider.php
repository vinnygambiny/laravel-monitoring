<?php

namespace Vinnygambiny\LaravelMonitoring\Providers;

use Aws\Sdk;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Vinnygambiny\LaravelMonitoring\Actions\GenerateAwsGraphAction;
use Vinnygambiny\LaravelMonitoring\Actions\GenerateAwsMetricAlarmsAction;
use Vinnygambiny\LaravelMonitoring\Console\InstallCommand;
use Vinnygambiny\LaravelMonitoring\Http\Middleware\HandleInertiaRequests;

class MonitoringServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerRoutes();
        $this->registerResources();
        $this->defineAssetPublishing();
    }

    protected function registerRoutes()
    {
        Route::group([
            'prefix' => config('monitoring.path', 'monitoring'),
            'middleware' => array_merge(
                config('monitoring.middleware', ['web']),
                [HandleInertiaRequests::class,]
            ),
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        });

        Route::group([
            'prefix' => config('monitoring.path', 'monitoring') . '/api',
            'middleware' => config('monitoring.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        });
    }

    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'monitoring');
    }

    protected function defineAssetPublishing()
    {
        $this->publishes([
            __DIR__ . '/../../public' => public_path('vendor/monitoring'),
        ], 'monitoring-assets');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/monitoring.php', 'monitoring'
        );

        $this->registerAwsSdk();

        if ($this->app->runningInConsole()) {
            $this->offerPublishing();
            $this->registerCommands();
        }
    }

    protected function offerPublishing()
    {
        $this->publishes([
            __DIR__.'/../../stubs/MonitoringServiceProvider.stub' => app_path('Providers/MonitoringServiceProvider.php'),
        ], 'monitoring-provider');

        $this->publishes([
            __DIR__.'/../../config/monitoring.php' => config_path('monitoring.php'),
        ], 'monitoring-config');
    }

    protected function registerCommands()
    {
        $this->commands([
            InstallCommand::class,
        ]);
    }

    protected function registerAwsSdk()
    {
        $this->app
            ->when([
                GenerateAwsGraphAction::class,
                GenerateAwsMetricAlarmsAction::class,
            ])
            ->needs(Sdk::class)
            ->give(function () {
                return new Sdk(config('monitoring.aws.config', []));
            });
    }
}
