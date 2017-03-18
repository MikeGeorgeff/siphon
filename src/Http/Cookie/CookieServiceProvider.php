<?php

namespace Siphon\Http\Cookie;

use Siphon\Foundation\ServiceProvider;

class CookieServiceProvider extends ServiceProvider
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
        $this->app->singleton('cookie', function ($app) {
            $config = $app['config']->get('cookie');

            return new Factory($config['domain'], $config['path'], $config['secure']);
        });

        $this->app->alias('cookie', Factory::class);
    }

    /**
     * Get the services provided
     *
     * @return array
     */
    public function provides()
    {
        return [Factory::class, 'cookie'];
    }
}