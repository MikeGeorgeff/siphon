<?php

namespace Siphon\Console\Commands\Database\Migration;

use Illuminate\Database\Migrations\Migrator;
use Symfony\Component\Console\Input\InputOption;

class Run extends Command
{
    /**
     * @var \Illuminate\Database\Migrations\Migrator
     */
    protected $migrator;

    /**
     * @param \Illuminate\Database\Migrations\Migrator $migrator
     */
    public function __construct(Migrator $migrator)
    {
        parent::__construct();

        $this->migrator = $migrator;
    }

    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('migration:run')
             ->setDescription('Run the database migrations.')
             ->addOption('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.')
             ->addOption('force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.')
             ->addOption('pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.')
             ->addOption('seed', null, InputOption::VALUE_NONE, 'Run the database seeder.')
             ->addOption('step', null, InputOption::VALUE_NONE, 'Force the migrations to run so they can be rolled back individually.');
    }

    /**
     * Handle the command
     *
     * @return void
     */
    public function handle()
    {
        if (! $this->confirmToProceed()) {
            return;
        }

        $this->prepareDatabase();

        $this->migrator->run($this->getMigrationPath(), [
            'pretend' => $this->option('pretend'),
            'step'    => $this->option('step')
        ]);

        foreach ($this->migrator->getNotes() as $note) {
            $this->writeln($note);
        }

        if ($this->option('seed')) {
            $this->call('seeder:run', ['--force' => true]);
        }
    }

    /**
     * Prepare the database for running
     *
     * @return void
     */
    protected function prepareDatabase()
    {
        $this->migrator->setConnection($this->option('database'));

        if (! $this->migrator->repositoryExists()) {
            $this->call('migration:install', ['--database' => $this->option('database')]);
        }
    }
}