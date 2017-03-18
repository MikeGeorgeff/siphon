<?php

namespace Siphon\Http\Session;

use Siphon\Foundation\ServiceProvider;
use Siphon\Http\Middleware\StartSession;

class SessionServiceProvider extends ServiceProvider
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
        $this->app->singleton('session.handler', function ($app) {
            return new CacheSessionHandler($app['cache'], $app['config']['session.lifetime']);
        });

        $this->app->singleton('session', function ($app) {
            $session = new Session($app['session.handler']);

            $session->setName($app['config']['session.cookie']);

            return $session;
        });

        $this->app->bind(StartSession::class, function ($app) {
            return new StartSession($app['session'], $app['cookie'], $app['config']['session.lifetime']);
        });

        $this->app->alias('session', Session::class);
    }

    /**
     * Get the services provided
     *
     * @return array
     */
    public function provides()
    {
        return [Session::class, StartSession::class, 'session', 'session.handler'];
    }
}