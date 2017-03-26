<?php

namespace Siphon\Http\Session;

use Siphon\Foundation\ServiceProvider;
use Siphon\Http\Middleware\StartSession;

class SessionServiceProvider extends ServiceProvider
{
    /**
     * Register container bindings
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('session.handler', function ($app) {
            return new CacheSessionHandler(
                $app['cache'],
                $app['config']['session.redis_connection'] ?: 'default',
                $app['config']['session.lifetime']
            );
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
}