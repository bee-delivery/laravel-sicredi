<?php 

namespace Beedelivery\Sicredi;

use Illuminate\Support\ServiceProvider;

class SicrediServiceProvider extends ServiceProvider 
{
    
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/sicredi.php' => config_path('sicredi.php'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/sicredi.php', 'sicredi');

        $this->app->singleton('sicredi', function ($app) {
            return new Sicredi;
        });
    }

    public function provides()
    {
        return ['sicredi'];
    }
}