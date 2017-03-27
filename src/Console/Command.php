<?php

namespace Siphon\Console;

use Siphon\Foundation\Application as Siphon;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class Command extends SymfonyCommand
{
    /**
     * @var \Siphon\Foundation\Application
     */
    protected $siphon;

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    protected $output;

    /**
     * Run the command
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        $this->input  = $input;
        $this->output = new SymfonyStyle($input, $output);

        return parent::run($this->input, $this->output);
    }

    /**
     * Call another command by name
     *
     * @param string $command
     * @param array  $parameters
     * @param bool   $silent
     * @return int
     */
    public function call($command, array $parameters = [], $silent = false)
    {
        $parameters['command'] = $command;

        $output = $silent ? new NullOutput : $this->output;

        return $this->getApplication()->find($command)->run(
            new ArrayInput($parameters), $output
        );
    }

    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure()
    {
    }

    /**
     * Execute the command
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     *
     * @throws \RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (! method_exists($this, 'handle')) {
            throw new \RuntimeException('Command class must implement a handle method.');
        }

        call_user_func([$this, 'handle']);
    }

    /**
     * Add an argument
     *
     * @param string $name
     * @param int    $mode
     * @param string $description
     * @param mixed  $default
     * @return \Siphon\Console\Command
     */
    public function addArgument($name, $mode = null, $description = '', $default = null)
    {
        parent::addArgument($name, $mode, $description, $default);

        return $this;
    }

    /**
     * Determine if the given argument exists
     *
     * @param string $name
     * @return bool
     */
    public function hasArgument($name)
    {
        return $this->input->hasArgument($name);
    }

    /**
     * Get all input arguments
     *
     * @return array
     */
    public function arguments()
    {
        return $this->input->getArguments();
    }

    /**
     * Get an input argument
     *
     * @param string $name
     * @return mixed
     */
    public function argument($name)
    {
        return $this->input->getArgument($name);
    }

    /**
     * Add an input option
     *
     * @param string $name
     * @param string $shortcut
     * @param int    $mode
     * @param string $description
     * @param mixed  $default
     * @return \Siphon\Console\Command
     */
    public function addOption($name, $shortcut = null, $mode = null, $description = '', $default = null)
    {
        parent::addOption($name, $shortcut, $mode, $description, $default);

        return $this;
    }

    /**
     * Determine if the given input option exists
     *
     * @param string $name
     * @return bool
     */
    public function hasOption($name)
    {
        return $this->input->hasOption($name);
    }

    /**
     * Get all input options
     *
     * @return array
     */
    public function options()
    {
        return $this->input->getOptions();
    }

    /**
     * Get an input option
     *
     * @param string $name
     * @return mixed
     */
    public function option($name)
    {
        return $this->input->getOption($name);
    }

    /**
     * Write a string to console output
     *
     * @param string $string
     * @return void
     */
    public function writeln($string)
    {
        $this->output->writeln($string);
    }

    /**
     * Write a string to console output
     *
     * @param string $string
     * @return void
     */
    public function info($string)
    {
        $this->output->block($string, null, 'fg=green');
    }

    /**
     * Write a string to console output
     *
     * @param string $string
     * @return void
     */
    public function note($string)
    {
        $this->output->block($string, null, 'fg=yellow');
    }

    /**
     * Write a string to console output
     *
     * @param string $string
     * @return void
     */
    public function error($string)
    {
        $this->output->block($string, null, 'fg=white;bg=red');
    }

    /**
     * Write a string to console output
     *
     * @param string $string
     * @return void
     */
    public function success($string)
    {
        $this->output->block($string, null, 'fg=black;bg=green');
    }

    /**
     * Ask a question
     *
     * @param string      $question
     * @param string|null $default
     * @return string
     */
    public function ask($question, $default = null)
    {
        return $this->output->ask($question, $default);
    }

    /**
     * Ask for confirmation
     *
     * @param string $question
     * @param bool   $default
     * @return bool
     */
    public function confirm($question, $default = false)
    {
        return $this->output->confirm($question, $default);
    }

    /**
     * Generate a confirmation message if the given condition is met
     *
     * @param string    $message
     * @param bool|null $condition
     * @return bool
     */
    public function confirmToProceed($message = 'Application in Production', $condition = null)
    {
        $shouldConfirm = is_null($condition) ?
            $this->siphon->environment() == 'production' : $condition;

        if ($shouldConfirm) {
            if ($this->option('force')) {
                return true;
            }

            $this->output->warning($message);

            $confirmed = $this->confirm('Do you really want to run this command?');

            if (! $confirmed) {
                $this->note('Command Canceled');

                return false;
            }
        }

        return true;
    }

    /**
     * Get the siphon application instance
     *
     * @return \Siphon\Foundation\Application
     */
    public function getSiphon()
    {
        return $this->siphon;
    }

    /**
     * Set the siphon application instance
     *
     * @param \Siphon\Foundation\Application $siphon
     * @return \Siphon\Console\Command
     */
    public function setSiphon(Siphon $siphon)
    {
        $this->siphon = $siphon;

        return $this;
    }
}