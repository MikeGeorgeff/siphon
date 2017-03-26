<?php

namespace Siphon\Console\Commands\Database\Migration;

use Symfony\Component\Console\Input\InputOption;

class Refresh extends Command
{
    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('migration:refresh')
             ->setDescription('Reset and re-run all migrations.')
             ->addOption('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.')
             ->addOption('force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.')
             ->addOption('seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run.')
             ->addOption('seeder', null, InputOption::VALUE_OPTIONAL, 'The class name of the root seeder.')
             ->addOption('step', null, InputOption::VALUE_OPTIONAL, 'The number of migrations to be reverted & re-run.');
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

        $database = $this->option('database');
        $force    = $this->option('force');
        $step     = $this->option('step') ?: 0;

        if ($step > 0) {
            $this->runRollback($database, $step, $force);
        } else {
            $this->runReset($database, $force);
        }

        $this->call('migration:run', [
            '--database' => $database,
            '--force'    => $force,
        ]);

        if ($this->needsSeeding()) {
            $this->runSeeder($database);
        }
    }

    /**
     * Run the rollback command
     *
     * @param  string  $database
     * @param  bool    $step
     * @param  bool    $force
     * @return void
     */
    protected function runRollback($database, $step, $force)
    {
        $this->call('migration:rollback', [
            '--database' => $database,
            '--step'     => $step,
            '--force'    => $force,
        ]);
    }

    /**
     * Run the reset command
     *
     * @param  string  $database
     * @param  bool    $force
     * @return void
     */
    protected function runReset($database, $force)
    {
        $this->call('migration:reset', [
            '--database' => $database,
            '--force'    => $force,
        ]);
    }

    /**
     * Determine if the developer has requested database seeding
     *
     * @return bool
     */
    protected function needsSeeding()
    {
        return $this->option('seed') || $this->option('seeder');
    }

    /**
     * Run the database seeder
     *
     * @param string $database
     * @return void
     */
    protected function runSeeder($database)
    {
        $this->call('seeder:run', [
            '--database' => $database,
            '--class'    => $this->option('seeder') ?: 'DatabaseSeeder',
            '--force'    => $this->option('force'),
        ]);
    }
}