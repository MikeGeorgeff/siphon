<?php

namespace Siphon\Debug\Event;

use Psr\Http\Message\ServerRequestInterface;

class ExceptionWasCaught
{
    /**
     * @var \Exception
     */
    public $exception;

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    public $request;

    /**
     * @param \Exception                               $exception
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function __construct(\Exception $exception, ServerRequestInterface $request)
    {
        $this->exception = $exception;
        $this->request   = $request;
    }
}