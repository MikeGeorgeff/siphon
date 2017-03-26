<?php

namespace Siphon\Console;

use Siphon\Foundation\Application as Siphon;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Application as SymfonyApp;

class Application extends SymfonyApp
{
    /**
     * @var \Siphon\Foundation\Application
     */
    protected $siphon;

    /**
     * @param \Siphon\Foundation\Application $siphon
     */
    public function __construct(Siphon $siphon)
    {
        parent::__construct('Siphon Framework', $siphon->version());

        $this->setAutoExit(false);
        $this->setCatchExceptions(false);

        $siphon['events']->dispatch(new Event\ConsoleStarting($this));

        $this->siphon = $siphon;
    }

    /**
     * Run a console command
     *
     * @param string $name
     * @param array  $parameters
     * @return int
     */
    public function call($name, array $parameters = [])
    {
        $parameters['command'] = $name;

        return $this->run(new ArrayInput($parameters), new BufferedOutput);
    }

    /**
     * Add a new command resolving it through the container
     *
     * @param string $command
     * @return \Siphon\Console\Command
     *
     * @throws \InvalidArgumentException
     */
    public function resolve($command)
    {
        $instance = $this->siphon->make($command);

        if ($instance instanceof Command) {
            $instance->setSiphon($this->siphon);

            return $this->add($instance);
        }

        throw new \InvalidArgumentException(
            'Command class must be an instance of Siphon\Console\Command'
        );
    }

    /**
     * Add an array of commands resolving them through the container
     *
     * @param array $commands
     * @return void
     */
    public function resolveCommands(array $commands)
    {
        foreach ($commands as $command) {
            $this->resolve($command);
        }
    }
}