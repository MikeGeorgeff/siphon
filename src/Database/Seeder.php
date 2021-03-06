<?php

namespace Siphon\Database;

use Siphon\Console\Command;
use Illuminate\Contracts\Container\Container;

abstract class Seeder
{
    /**
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * @var \Siphon\Console\Command
     */
    protected $command;

    /**
     * Seed the given connection from the given path
     *
     * @param  string  $class
     * @return void
     */
    public function call($class)
    {
        if (isset($this->command)) {
            $this->command->writeln("<info>Seeding:</info> $class");
        }

        $this->resolve($class)->__invoke();
    }

    /**
     * Resolve an instance of the given seeder class
     *
     * @param  string  $class
     * @return \Siphon\Database\Seeder
     */
    protected function resolve($class)
    {
        if (isset($this->container)) {
            $instance = $this->container->make($class);

            $instance->setContainer($this->container);
        } else {
            $instance = new $class;
        }

        if (isset($this->command)) {
            $instance->setCommand($this->command);
        }

        return $instance;
    }

    /**
     * Set the container instance
     *
     * @param \Illuminate\Contracts\Container\Container $container
     * @return \Siphon\Database\Seeder
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Set the command instance
     *
     * @param \Siphon\Console\Command $command
     * @return \Siphon\Database\Seeder
     */
    public function setCommand(Command $command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Run the database seeds
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function __invoke()
    {
        if (! method_exists($this, 'run')) {
            throw new InvalidArgumentException('Method [run] missing from '.get_class($this));
        }

        return isset($this->container)
            ? $this->container->call([$this, 'run'])
            : $this->run();
    }
}