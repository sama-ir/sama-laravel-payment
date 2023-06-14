<?php

namespace TosanSoha\Sama;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->publishes([__DIR__.'/../config/sama.php' => config_path('sama.php')], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sama.php', 'sama');
    }
}
