<?php

namespace Siphon\Cache;

use Siphon\Foundation\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
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
        $this->app->singleton('cache.store', function ($app) {
            return new RedisStore($app['redis']);
        });

        $this->app->bind('cache', function ($app) {
            return new Repository($app['cache.store']);
        });

        $this->app->alias('cache.store', Store::class);
        $this->app->alias('cache', Repository::class);
    }

    /**
     * Get the services provided
     *
     * @return array
     */
    public function provides()
    {
        return [Store::class, Repository::class, 'cache.store', 'cache'];
    }
}