<?php

namespace Siphon\Console\Commands\App;

use Siphon\Console\Command;

class Down extends Command
{
    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('app:down')
             ->setDescription('Put the application in maintenance mode.');
    }

    /**
     * Handle the command
     *
     * @return void
     */
    public function handle()
    {
        file_put_contents($this->siphon->storagePath().'/framework/down', '');

        $this->info('The application is now in maintenance mode.');
    }
}