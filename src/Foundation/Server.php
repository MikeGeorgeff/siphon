<?php

namespace Siphon\Foundation;

abstract class Server
{
    /**
     * @var \Siphon\Foundation\Application
     */
    protected $app;

    /**
     * The application base install path
     *
     * @var string
     */
    protected $basePath;

    /**
     * Create a new server instance
     *
     * @param string $basePath
     */
    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * Get the application instance
     *
     * @return \Siphon\Foundation\Application
     */
    public function getApp()
    {
        if (isset($this->app)) {
            return $this->app;
        }

        $app = new Application($this->basePath);

        $app['config']->set($this->configure($app));

        date_default_timezone_set($app['config']['app.timezone']);

        $app->instance('env', $app['config']['app.env']);

        $this->providers($app);

        $app->boot();

        $this->app = $app;

        return $app;
    }

    /**
     * Register service providers
     *
     * @param \Siphon\Foundation\Application $app
     * @return void
     */
    protected function providers(Application $app)
    {
        $app->register(Provider\LogServiceProvider::class);
        $app->register(\Siphon\Bus\Provider\BusServiceProvider::class);
        $app->register(\Siphon\Cache\CacheServiceProvider::class);
        $app->register(\Siphon\Database\DatabaseServiceProvider::class);
        $app->register(\Illuminate\Encryption\EncryptionServiceProvider::class);
        $app->register(\Siphon\Debug\ExceptionHandler::class);
        $app->register(\Illuminate\Filesystem\FilesystemServiceProvider::class);
        $app->register(\Illuminate\Hashing\HashServiceProvider::class);
        $app->register(\Siphon\Http\Cookie\CookieServiceProvider::class);
        $app->register(\Siphon\Http\Response\ResponseServiceProvider::class);
        $app->register(\Siphon\Http\Routing\RoutingServiceProvider::class);
        $app->register(\Siphon\Http\Session\SessionServiceProvider::class);
        $app->register(\Siphon\Redis\RedisServiceProvider::class);
        $app->register(\Siphon\View\ViewServiceProvider::class);
    }

    /**
     * Array of configuration values to load into the application
     *
     * @param \Siphon\Foundation\Application $app
     * @return array
     */
    abstract protected function configure(Application $app);
}