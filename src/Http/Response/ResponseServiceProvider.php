<?php

namespace Siphon\Http\Response;

use Siphon\Foundation\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register container bindings
     *
     * @return void
     */
    public function register()
    {
        $this->registerFactory();

        $this->registerAliases();
    }

    /**
     * Register the response factory
     *
     * @return void
     */
    protected function registerFactory()
    {
        $this->app->bind('response', function ($app) {
            $factory = new Factory($app['redirector']);

            if ($app->bound('view')) {
                $factory->setView($app['view']);
            }

            if ($app->bound('session')) {
                $factory->setSession($app['session']);
            }

            return $factory;
        });
    }

    /**
     * Register container aliases
     *
     * @return void
     */
    protected function registerAliases()
    {
        $this->app->alias('response', Factory::class);
        $this->app->alias('response', FactoryInterface::class);
    }
}