<?php

namespace Siphon\Console\Commands\App;

use Siphon\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class Key extends Command
{
    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('app:key')
             ->setDescription('Set the application key.')
             ->addOption('show', null, InputOption::VALUE_NONE, 'Show the key without writing to file.')
             ->addOption('force', null, InputOption::VALUE_NONE, 'Overwrite the existing key if there is one.');
    }

    /**
     * Handle the command
     *
     * @return void
     */
    public function handle()
    {
        $key = $this->generateRandomKey();

        if ($this->option('show')) {
            return $this->note($key);
        }

        $currentKey = $this->siphon['config']['app.key'];

        if (strlen($currentKey) > 0 && ! $this->option('force')) {
            return $this->error('Application key has already been set.');
        }

        $this->writeKeyToEnvFile($key);

        $this->info('Application key ['.$key.'] has been set.');
    }

    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    protected function generateRandomKey()
    {
        return 'base64:'.base64_encode(
            random_bytes($this->siphon['config']['app.cipher'] == 'AES-128-CBC' ? 16 : 32)
        );
    }

    /**
     * Write the key to the env file
     *
     * @param  string  $key
     * @return void
     */
    protected function writeKeyToEnvFile($key)
    {
        file_put_contents($this->siphon->basePath().'/.env', preg_replace(
            $this->keyReplacementPattern(),
            'APP_KEY='.$key,
            file_get_contents($this->siphon->basePath().'/.env')
        ));
    }

    /**
     * Get a regex pattern that will match env APP_KEY with any random key.
     *
     * @return string
     */
    protected function keyReplacementPattern()
    {
        $escaped = preg_quote('='.$this->siphon['config']['app.key'], '/');

        return "/^APP_KEY{$escaped}/m";
    }
}