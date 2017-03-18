<?php

namespace Siphon\Http\Middleware;

use Siphon\Debug\ExceptionHandler;
use Zend\Stratigility\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HandleException implements MiddlewareInterface
{
    /**
     * @var \Siphon\Debug\ExceptionHandler
     */
    protected $handler;

    /**
     * @param \Siphon\Debug\ExceptionHandler $handler
     */
    public function __construct(ExceptionHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        try {
            return $next($request, $response);
        } catch (\Exception $e) {
            return $this->handler->handle($e, $request);
        }
    }
}