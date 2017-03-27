<?php

namespace Siphon\Console\Commands\Database\Seeder;

use Siphon\Console\Command;
use Illuminate\Support\Composer;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;

class Make extends Command
{
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $file;

    /**
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * @param \Illuminate\Filesystem\Filesystem $file
     * @param \Illuminate\Support\Composer      $composer
     */
    public function __construct(Filesystem $file, Composer $composer)
    {
        parent::__construct();

        $this->file     = $file;
        $this->composer = $composer;
    }

    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('seeder:make')
             ->setDescription('Generate a new seeder file.')
             ->addArgument('name', InputArgument::REQUIRED, 'The name of the class');
    }

    /**
     * Handle the command
     *
     * @return void
     */
    public function handle()
    {
        $name = $this->argument('name');

        $path = $this->getPath($name);

        if ($this->file->exists($path)) {
            $this->error('Seeder file ['.$name.'] already exists.');

            return;
        }

        $this->file->put($path, $this->getFileContents($name));

        $this->note('Seeder file ['.$name.'] created successfully.');

        $this->composer->dumpAutoloads();
    }

    /**
     * Get the content for the seeder class
     *
     * @param string $name
     * @return string
     */
    protected function getFileContents($name)
    {
        $stub = $this->file->get($this->getStub());

        return str_replace('DummyClass', $name, $stub);
    }

    /**
     * Get the seeder stub file
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stub/seeder.stub';
    }

    /**
     * Get the destination class path
     *
     * @param string $name
     * @return string
     */
    protected function getPath($name)
    {
        return $this->siphon->databasePath().'/seeds/'.$name.'.php';
    }
}