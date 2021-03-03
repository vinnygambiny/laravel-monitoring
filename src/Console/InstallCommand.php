<?php

namespace Vinnygambiny\LaravelMonitoring\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    protected $signature = 'monitoring:install';

    protected $description = 'Install all of the Laravel-Monitoring resources';

    public function handle()
    {
        $this->comment('Publishing Monitoring Service Provider...');
        $this->callSilent('vendor:publish', ['--tag' => 'monitoring-provider']);

        $this->comment('Publishing Monitoring Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'monitoring-assets']);

        $this->comment('Publishing Monitoring Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'monitoring-config']);

        $this->registerMonitoringServiceProvider();

        $this->info('Monitoring scaffolding installed successfully.');
    }

    protected function registerMonitoringServiceProvider()
    {
        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace.'\\Providers\\MonitoringServiceProvider::class')) {
            return;
        }

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL,
            "{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL."        {$namespace}\Providers\MonitoringServiceProvider::class,".PHP_EOL,
            $appConfig
        ));

        file_put_contents(app_path('Providers/MonitoringServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/MonitoringServiceProvider.php'))
        ));
    }
}
