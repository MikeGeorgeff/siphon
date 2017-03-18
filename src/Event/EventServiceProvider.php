<?php

namespace Siphon\Event;

use Siphon\Foundation\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register container bindings
     *
     * @return void
     */
    public function register()
    {
        $this->registerDispatcher();

        $this->registerAliases();
    }

    /**
     * Register the dispatcher instance
     *
     * @return void
     */
    public function registerDispatcher()
    {
        $this->app->singleton('events', function ($app) {
            return new \Illuminate\Events\Dispatcher($app);
        });

        $this->app->singleton(Dispatcher::class, function ($app) {
            return new Dispatcher($app['events']);
        });
    }

    /**
     * Register container aliases
     *
     * @return void
     */
    protected function registerAliases()
    {
        $this->app->alias('events', \Illuminate\Events\Dispatcher::class);
        $this->app->alias('events', \Illuminate\Contracts\Events\Dispatcher::class);
        $this->app->alias(Dispatcher::class, 'siphon.events');
    }
}