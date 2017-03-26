<?php

namespace Siphon\Http\Event;

use Psr\Http\Message\ServerRequestInterface;

class DownForMaintenance
{
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    public $request;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }
}