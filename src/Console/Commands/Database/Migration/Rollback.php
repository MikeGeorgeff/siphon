<?php

namespace Siphon\Console\Commands\Database\Migration;

use Illuminate\Database\Migrations\Migrator;
use Symfony\Component\Console\Input\InputOption;

class Rollback extends Command
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
        $this->setName('migration:rollback')
             ->setDescription('Rollback the last database migration')
             ->addOption('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.')
             ->addOption('force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.')
             ->addOption('pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.')
             ->addOption('step', null, InputOption::VALUE_OPTIONAL, 'The number of migrations to be reverted.');
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

        $this->migrator->rollback($this->getMigrationPath(), [
            'pretend' => $this->option('pretend'),
            'step'    => $this->option('step')
        ]);

        foreach ($this->migrator->getNotes() as $note) {
            $this->writeln($note);
        }
    }
}