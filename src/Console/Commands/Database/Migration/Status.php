<?php

namespace Siphon\Console\Commands\Database\Migration;

use Illuminate\Support\Collection;
use Illuminate\Database\Migrations\Migrator;
use Symfony\Component\Console\Input\InputOption;

class Status extends Command
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
        $this->setName('migration:status')
             ->setDescription('Show the status of each migration.')
             ->addOption('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.');
    }

    /**
     * Handle the command
     *
     * @return void
     */
    public function handle()
    {
        $this->migrator->setConnection($this->option('database'));

        if (! $this->migrator->repositoryExists()) {
            return $this->note('No migrations found.');
        }

        $ran = $this->migrator->getRepository()->getRan();

        if (count($migrations = $this->getStatusFor($ran)) > 0) {
            $this->output->table(['Ran', 'Migration'], $migrations);
        } else {
            $this->note('No migrations found.');
        }
    }

    /**
     * Get the status for the given ran migrations.
     *
     * @param array $ran
     * @return \Illuminate\Support\Collection
     */
    protected function getStatusFor(array $ran)
    {
        return Collection::make($this->getAllMigrationFiles())
                         ->map(function ($migration) use ($ran) {
                             $migrationName = $this->migrator->getMigrationName($migration);

                             return in_array($migrationName, $ran)
                                 ? ['<info>Y</info>', $migrationName]
                                 : ['<fg=red>N</fg=red>', $migrationName];
                         });
    }

    /**
     * Get all of the migration files
     *
     * @return array
     */
    protected function getAllMigrationFiles()
    {
        return $this->migrator->getMigrationFiles($this->getMigrationPath());
    }
}