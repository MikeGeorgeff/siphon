<?php

namespace Siphon\Foundation\Provider;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Siphon\Foundation\ServiceProvider;

class LogServiceProvider extends ServiceProvider
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
        $this->app->singleton('log', function ($app) {
            $path = $app->storagePath().'/logs/siphon.log';

            $logger = new Logger($app->environment());

            $handler = new StreamHandler($path, Logger::DEBUG);
            $handler->setFormatter(new LineFormatter(null, null, true, true));

            $logger->pushHandler($handler);

            return $logger;
        });

        $this->app->alias('log', Logger::class);
        $this->app->alias('log', LoggerInterface::class);
    }

    /**
     * Get the services provided
     *
     * @return array
     */
    public function provides()
    {
        return [LoggerInterface::class, Logger::class, 'log'];
    }
}