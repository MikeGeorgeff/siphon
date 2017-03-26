<?php

namespace Siphon\Console\Commands\Database\Migration;

use Illuminate\Support\Composer;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Database\Migrations\MigrationCreator;

class Make extends Command
{
    /**
     * @var \Illuminate\Database\Migrations\MigrationCreator
     */
    protected $creator;

    /**
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * @param \Illuminate\Database\Migrations\MigrationCreator $creator
     * @param \Illuminate\Support\Composer                     $composer
     */
    public function __construct(MigrationCreator $creator, Composer $composer)
    {
        parent::__construct();

        $this->creator  = $creator;
        $this->composer = $composer;
    }

    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('migration:make')
             ->setDescription('Generate a new migration file.')
             ->addArgument('name', InputArgument::REQUIRED, 'The name of the migration.')
             ->addOption('create', null, InputOption::VALUE_OPTIONAL, 'The table to be created.')
             ->addOption('table', null, InputOption::VALUE_OPTIONAL, 'The table to migrate.');
    }

    /**
     * Handle the command
     *
     * @return void
     */
    public function handle()
    {
        $name = trim($this->argument('name'));

        $table = $this->option('table');

        $create = $this->option('create') ?: false;

        if (! $table && is_string($create)) {
            $table = $create;

            $create = true;
        }

        $this->writeMigration($name, $table, $create);

        $this->composer->dumpAutoloads();
    }

    /**
     * Write the migration file
     *
     * @param  string  $name
     * @param  string  $table
     * @param  bool    $create
     * @return string
     */
    protected function writeMigration($name, $table, $create)
    {
        $file = pathinfo($this->creator->create(
            $name, $this->getMigrationPath(), $table, $create
        ), PATHINFO_FILENAME);

        $this->writeln('<info>Created Migration:</info> '.$file);
    }
}