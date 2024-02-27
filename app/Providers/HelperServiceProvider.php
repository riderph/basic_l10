<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * The service provider register helper
 * Please register this service provider in the config/app.php > providers
 */
class HelperServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach (glob(__DIR__ . '/../Helpers/*.php') as $filename) {
            require_once($filename);
        }
    }
}
