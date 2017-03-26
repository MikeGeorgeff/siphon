<?php

namespace Siphon\Console\Commands\Database\Migration;

use Symfony\Component\Console\Input\InputOption;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;

class Install extends Command
{
    /**
     * @var \Illuminate\Database\Migrations\MigrationRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Illuminate\Database\Migrations\MigrationRepositoryInterface $repository
     */
    public function __construct(MigrationRepositoryInterface $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('migration:install')
             ->setDescription('Create the migration repository.')
             ->addOption('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.');
    }

    /**
     * Handle the command
     *
     * @return void
     */
    public function handle()
    {
        $this->repository->setSource($this->option('database'));

        $this->repository->createRepository();

        $this->info('Migration table created successfully.');
    }
}