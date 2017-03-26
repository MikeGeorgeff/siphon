<?php

namespace Siphon\Console\Commands\Database\Migration;

use Illuminate\Database\Migrations\Migrator;
use Symfony\Component\Console\Input\InputOption;

class Reset extends Command
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
        $this->setName('migration:reset')
             ->setDescription('Rollback all database migrations.')
             ->addOption('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.')
             ->addOption('force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.')
             ->addOption('pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.');
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

        $this->migrator->setConnection($this->option('database'));

        if (! $this->migrator->repositoryExists()) {
            return $this->note('Migration repository does not exist.');
        }

        $this->migrator->reset($this->getMigrationPath(), $this->option('pretend'));

        foreach ($this->migrator->getNotes() as $note) {
            $this->writeln($note);
        }
    }
}