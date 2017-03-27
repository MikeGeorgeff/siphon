<?php

namespace Siphon\Debug;

use Siphon\Foundation\ServiceProvider;

class DebugServiceProvider extends ServiceProvider
{
    /**
     * Determine if loading of the provider is deferred
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register container bindings
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ExceptionHandler::class, function ($app) {
            $debug = $app['config']['app.debug'];

            return new ExceptionHandler(
                $app['siphon.events'],
                $app['log'],
                $app['response'],
                $debug
            );
        });
    }

    /**
     * Get the services provided
     *
     * @return array
     */
    public function provides()
    {
        return [ExceptionHandler::class];
    }
}