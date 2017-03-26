<?php

namespace Siphon\Http;

use Siphon\Foundation\Application;

abstract class ApiServer extends Server
{
    /**
     * Register service providers
     *
     * @param \Siphon\Foundation\Application $app
     * @return void
     */
    protected function providers(Application $app)
    {
        $app->register(\Siphon\Foundation\Provider\LogServiceProvider::class);
        $app->register(\Siphon\Bus\Provider\BusServiceProvider::class);
        $app->register(\Siphon\Cache\CacheServiceProvider::class);
        $app->register(\Siphon\Database\DatabaseServiceProvider::class);
        $app->register(\Siphon\Foundation\Provider\EncryptionServiceProvider::class);
        $app->register(\Siphon\Debug\DebugServiceProvider::class);
        $app->register(\Siphon\Foundation\Provider\FilesystemServiceProvider::class);
        $app->register(\Siphon\Foundation\Provider\HashServiceProvider::class);
        $app->register(\Siphon\Http\Response\ResponseServiceProvider::class);
        $app->register(\Siphon\Http\Routing\RoutingServiceProvider::class);
        $app->register(\Siphon\Redis\RedisServiceProvider::class);
    }
}