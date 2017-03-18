<?php

namespace Siphon\Http\Routing;

use Psr\Http\Message\ServerRequestInterface;

interface ActionInterface
{
    /**
     * Invoke the action
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request);
}