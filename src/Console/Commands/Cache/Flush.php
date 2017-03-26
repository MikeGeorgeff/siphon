<?php

namespace Siphon\Console\Commands\Cache;

use Siphon\Console\Command;
use Siphon\Cache\Repository;
use Symfony\Component\Console\Input\InputOption;

class Flush extends Command
{
    /**
     * @var \Siphon\Cache\Repository
     */
    protected $cache;

    /**
     * @param \Siphon\Cache\Repository $cache
     */
    public function __construct(Repository $cache)
    {
        parent::__construct();

        $this->cache = $cache;
    }

    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('cache:flush')
             ->setDescription('Flush the application\'s cache.')
             ->addOption('connection', null, InputOption::VALUE_OPTIONAL, 'The redis connection to flush.', 'default');
    }

    /**
     * Handle the command
     *
     * @return void
     */
    public function handle()
    {
        $this->cache->setConnection($connection = $this->option('connection'));

        $this->cache->flush();

        $this->info(sprintf(
            'The cache has been flushed for connection %s.', $connection
        ));
    }
}