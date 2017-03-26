<?php

namespace Siphon\Database;

use Illuminate\Database\Connection;
use Siphon\Foundation\ServiceProvider;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Connectors\ConnectionFactory;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register container bindings
     *
     * @return void
     */
    public function register()
    {
        Model::clearBootedModels();

        $this->app->singleton('db.factory', function ($app) {
            return new ConnectionFactory($app);
        });

        $this->app->singleton('db', function ($app) {
            return new DatabaseManager($app, $app['db.factory']);
        });

        $this->app->bind('db.connection', function ($app) {
            return $app['db']->connection();
        });

        $this->registerAliases();
    }

    /**
     * Bootstrap application services
     *
     * @return void
     */
    public function boot()
    {
        Model::setConnectionResolver($this->app['db']);

        Model::setEventDispatcher($this->app['events']);
    }

    /**
     * Register container aliases
     *
     * @return void
     */
    protected function registerAliases()
    {
        $this->app->alias('db', DatabaseManager::class);
        $this->app->alias('db', ConnectionResolverInterface::class);
        $this->app->alias('db.connection', Connection::class);
        $this->app->alias('db.connection', ConnectionInterface::class);
    }
}