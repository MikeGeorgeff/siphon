<?php

namespace Siphon\Console\Commands\Cache;

use Siphon\Console\Command;
use Siphon\Cache\Repository;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Remove extends Command
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
        $this->setName('cache:remove')
             ->setDescription('Remove an item from the application cache by it\'s key.')
             ->addArgument('key', InputArgument::REQUIRED, 'The item key to remove.')
             ->addOption('connection', null, InputOption::VALUE_OPTIONAL, 'The redis connection the key is saved on.', 'default');
    }

    /**
     * Handle the command
     *
     * @return void
     */
    public function handle()
    {
        $this->cache->setConnection($this->option('connection'));

        $this->cache->remove($this->argument('key'));

        $this->info('Item successfully removed from the cache.');
    }
}