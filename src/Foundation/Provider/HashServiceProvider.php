<?php

namespace Siphon\Foundation\Provider;

use Illuminate\Hashing\BcryptHasher;
use Siphon\Foundation\ServiceProvider;
use Illuminate\Contracts\Hashing\Hasher;

class HashServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('hash', function () {
            return new BcryptHasher;
        });

        $this->app->alias('hash', Hasher::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['hash', Hasher::class];
    }
}