<?php

namespace Siphon\Config;

use Illuminate\Config\Repository;
use Siphon\Foundation\ServiceProvider;
use Illuminate\Contracts\Config\Repository as RepositoryContract;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register container bindings
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('config', function () {
            return new Repository;
        });

        $this->app->alias('config', Repository::class);
        $this->app->alias('config', RepositoryContract::class);
    }
}