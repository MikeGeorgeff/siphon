<?php

namespace Siphon\View;

use League\Plates\Engine;
use League\Plates\Extension\Asset;
use Siphon\Foundation\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Determine if loading of the provider is deferred
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Extensions to load
     *
     * @var array
     */
    protected $extensions = [
        Extension\Url::class,
        Extension\Session::class,
    ];

    /**
     * Register container bindings
     *
     * @return void
     */
    public function register()
    {
        $this->registerExtensions();

        $this->registerViewEngine();

        $this->registerViewRenderer();
    }

    /**
     * Register the view renderer
     *
     * @return void
     */
    protected function registerViewRenderer()
    {
        $this->app->singleton('view', function ($app) {
            return new PlatesRenderer($app['view.engine']);
        });

        $this->app->alias('view', Renderer::class);
    }

    /**
     * Register the view engine
     *
     * @return void
     */
    protected function registerViewEngine()
    {
        $this->app->bind('view.engine', function ($app) {
            $engine = new Engine($app->config('view.path'));

            $engine->loadExtension(new Asset($app->publicPath()));

            foreach ($this->extensions as $extension) {
                $engine->loadExtension($app->make($extension));
            }

            return $engine;
        });
    }

    /**
     * Get the services provided
     *
     * @return array
     */
    public function provides()
    {
        return [Renderer::class, 'view', 'view.engine'];
    }
}