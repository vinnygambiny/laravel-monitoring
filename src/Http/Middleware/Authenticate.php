<?php

namespace Vinnygambiny\LaravelMonitoring\Http\Middleware;

use Illuminate\Support\Facades\Gate;

class Authenticate
{
    public function handle($request, $next)
    {
        return $this->check($request) ? $next($request) : abort(403);
    }

    protected function check($request)
    {
        return app()->environment('local') ||
            Gate::check('viewMonitoring', [$request->user()]);
    }
}
