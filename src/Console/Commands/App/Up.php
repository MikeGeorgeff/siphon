<?php

namespace Siphon\Console\Commands\App;

use Siphon\Console\Command;

class Up extends Command
{
    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('app:up')
             ->setDescription('Take the application out of maintenance mode.');
    }

    /**
     * Handle the command
     *
     * @return void
     */
    public function handle()
    {
        @unlink($this->siphon->storagePath().'/framework/down');

        $this->info('The application is now live.');
    }
}