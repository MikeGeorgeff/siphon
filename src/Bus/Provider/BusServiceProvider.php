<?php

namespace Siphon\Bus\Provider;

use Siphon\Bus\Inner\Bus;
use Siphon\Bus\CommandBus;
use Siphon\Bus\Dispatcher;
use Siphon\Bus\HandlerResolver;
use Siphon\Bus\ContainerInterface;
use Siphon\Bus\MethodNameInflector;
use Siphon\Foundation\ServiceProvider;
use Siphon\Bus\Resolver\DefaultResolver;
use Siphon\Bus\Inflector\HandleInflector;
use Siphon\Bus\Container\IlluminateContainer;

class BusServiceProvider extends ServiceProvider
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
        $this->registerContainer();

        $this->registerResolver();

        $this->registerInflector();

        $this->registerInnerBus();

        $this->registerCommandBus();
    }

    /**
     * Register the container
     *
     * @return void
     */
    protected function registerContainer()
    {
        $this->app->bind(ContainerInterface::class, function ($app) {
            return new IlluminateContainer($app);
        });
    }

    /**
     * Register the handler resolver
     *
     * @return void
     */
    protected function registerResolver()
    {
        $this->app->bind(HandlerResolver::class, function ($app) {
            return new DefaultResolver($app[ContainerInterface::class]);
        });
    }

    /**
     * Register the inflector
     *
     * @return void
     */
    protected function registerInflector()
    {
        $this->app->bind(MethodNameInflector::class, function ($app) {
            return new HandleInflector;
        });
    }

    /**
     * Register the inner bus
     *
     * @return void
     */
    protected function registerInnerBus()
    {
        $this->app->bind('bus.inner', function ($app) {
            return new Bus($app[HandlerResolver::class], $app[MethodNameInflector::class]);
        });
    }

    /**
     * Register the command bus instance
     *
     * @return void
     */
    protected function registerCommandBus()
    {
        $this->app->bind(CommandBus::class, function ($app) {
            return new Dispatcher($app['bus.inner']);
        });
    }

    /**
     * Get the services provided
     *
     * @return array
     */
    public function provides()
    {
        return [
            ContainerInterface::class,
            HandlerResolver::class,
            MethodNameInflector::class,
            CommandBus::class,
            'bus.inner'
        ];
    }
}