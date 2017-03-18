<?php

namespace Siphon\Http\Routing;

use Siphon\Foundation\ServiceProvider;

class RoutingServiceProvider extends ServiceProvider
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
        $this->registerActionResolver();

        $this->registerRouteCollection();

        $this->registerRouter();

        $this->registerUrlGenerator();

        $this->registerRedirector();

        $this->registerAliases();
    }

    /**
     * Register the action resolver
     *
     * @return void
     */
    protected function registerActionResolver()
    {
        $this->app->bind('action.resolver', function ($app) {
            return new ActionResolver($app);
        });
    }

    /**
     * Register the route collection
     *
     * @return void
     */
    protected function registerRouteCollection()
    {
        $this->app->singleton('route.collection', function () {
            return new RouteCollection;
        });
    }

    /**
     * Register the router
     *
     * @return void
     */
    protected function registerRouter()
    {
        $this->app->bind('router', function ($app) {
            return new Router($app['route.collection'], $app['action.resolver']);
        });
    }

    /**
     * Register the url generator
     *
     * @return void
     */
    protected function registerUrlGenerator()
    {
        $this->app->bind('url', function ($app) {
            return new UrlGenerator($app['route.collection']);
        });
    }

    /**
     * Register the redirector
     *
     * @return void
     */
    protected function registerRedirector()
    {
        $this->app->bind('redirector', function ($app) {
            return new Redirector($app['url']);
        });
    }

    /**
     * Register container aliases
     *
     * @return void
     */
    protected function registerAliases()
    {
        $array = [
            'action.resolver'  => ActionResolver::class,
            'route.collection' => RouteCollection::class,
            'router'           => Router::class,
            'url'              => UrlGenerator::class,
            'redirector'       => Redirector::class
        ];

        foreach ($array as $abstract => $alias) {
            $this->app->alias($abstract, $alias);
        }
    }

    /**
     * Get the services provided
     *
     * @return array
     */
    public function provides()
    {
        return [
            ActionResolver::class,
            RouteCollection::class,
            Router::class,
            UrlGenerator::class,
            Redirector::class,
            'action.resolver',
            'route.collection',
            'router',
            'url',
            'redirector'
        ];
    }
}