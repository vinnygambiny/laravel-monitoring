<?php

namespace Vinnygambiny\LaravelMonitoring\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Vinnygambiny\LaravelMonitoring\Http\Middleware\Authenticate;

class Controller extends BaseController
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }
}
