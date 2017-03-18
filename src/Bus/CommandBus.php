<?php

namespace Siphon\Bus;

interface CommandBus
{
    /**
     * Execute the handler for the given command
     * 
     * @param object $command
     * @return mixed
     */
    public function execute($command);
}