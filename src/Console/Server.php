<?php

namespace Siphon\Console;

use Siphon\Foundation\Application;
use Siphon\Console\Application as Console;

abstract class Server extends \Siphon\Foundation\Server
{
    /**
     * Run the console application
     *
     * @return void
     */
    public function listen()
    {
        $console = $this->getConsoleApplication();

        exit($console->run());
    }

    /**
     * Get the console application instance
     *
     * @return \Siphon\Console\Application
     */
    protected function getConsoleApplication()
    {
        $console = new Console($this->getApp());

        $this->commands($console);

        return $console;
    }

    /**
     * Register console commands
     *
     * @param \Siphon\Console\Application $app
     * @return void
     */
    protected function commands(Console $app)
    {
        $app->resolve(Commands\App\Up::class);
        $app->resolve(Commands\App\Key::class);
        $app->resolve(Commands\App\Down::class);
        $app->resolve(Commands\Cache\Flush::class);
        $app->resolve(Commands\Cache\Remove::class);
        $app->resolve(Commands\Database\Seeder\Run::class);
        $app->resolve(Commands\Database\Seeder\Make::class);
        $app->resolve(Commands\Database\Migration\Run::class);
        $app->resolve(Commands\Database\Migration\Make::class);
        $app->resolve(Commands\Database\Migration\Reset::class);
        $app->resolve(Commands\Database\Migration\Status::class);
        $app->resolve(Commands\Database\Migration\Install::class);
        $app->resolve(Commands\Database\Migration\Refresh::class);
        $app->resolve(Commands\Database\Migration\Rollback::class);
    }

    /**
     * Register service providers
     *
     * @param \Siphon\Foundation\Application $app
     * @return void
     */
    protected function providers(Application $app)
    {
        $app->register(\Siphon\Bus\Provider\BusServiceProvider::class);
        $app->register(\Siphon\Cache\CacheServiceProvider::class);
        $app->register(\Siphon\Database\DatabaseServiceProvider::class);
        $app->register(\Siphon\Database\MigrationServiceProvider::class);
        $app->register(\Illuminate\Encryption\EncryptionServiceProvider::class);
        $app->register(\Siphon\Debug\DebugServiceProvider::class);
        $app->register(\Illuminate\Filesystem\FilesystemServiceProvider::class);
        $app->register(\Illuminate\Hashing\HashServiceProvider::class);
        $app->register(\Siphon\Redis\RedisServiceProvider::class);
    }
}