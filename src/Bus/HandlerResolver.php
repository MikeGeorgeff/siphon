<?php

namespace Siphon\Bus;

interface HandlerResolver
{
    /**
     * Resolve the handler from the command object
     *
     * @param object $command
     * @return object
     */
    public function resolve($command);
}