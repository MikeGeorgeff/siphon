<?php

namespace Siphon\Console\Event;

use Siphon\Console\Application;

class ConsoleStarting
{
    /**
     * @var \Siphon\Console\Application
     */
    public $app;

    /**
     * @param \Siphon\Console\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }
}