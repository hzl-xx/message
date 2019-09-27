<?php

namespace Aiqbg\Message;

use Illuminate\Support\ServiceProvider;

class MessageProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->publishes([
            __DIR__.'/config/messageserver.php' => config_path('messageserver.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton('message', function ($app) {
            return new Message($app['config']);
        });
    }
}
