<?php

namespace Siphon\Console\Commands\Database\Seeder;

use Siphon\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Database\ConnectionResolverInterface;

class Run extends Command
{
    /**
     * @var \Illuminate\Database\ConnectionResolverInterface
     */
    protected $resolver;

    /**
     * @param \Illuminate\Database\ConnectionResolverInterface $resolver
     */
    public function __construct(ConnectionResolverInterface $resolver)
    {
        parent::__construct();

        $this->resolver = $resolver;
    }

    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('seeder:run')
            ->setDescription('Run the database seeder.')
            ->addOption('class', null, InputOption::VALUE_OPTIONAL, 'The class name of the root seeder.', 'DatabaseSeeder')
            ->addOption('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to seed.')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.');
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

        $this->resolver->setDefaultConnection($this->getDatabase());

        Model::unguarded(function () {
            $this->getSeeder()->__invoke();
        });
    }

    /**
     * Get a seeder instance from the container.
     *
     * @return \Siphon\Database\Seeder
     */
    protected function getSeeder()
    {
        $class = $this->siphon->make($this->option('class'));

        return $class->setContainer($this->siphon)->setCommand($this);
    }

    /**
     * Get the name of the database connection to use.
     *
     * @return string
     */
    protected function getDatabase()
    {
        $database = $this->option('database');

        return $database ?: $this->siphon['config']['database.default'];
    }
}