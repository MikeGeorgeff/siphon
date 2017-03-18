<?php

namespace Siphon\Http;

use Siphon\Foundation\Application;
use Zend\Stratigility\MiddlewarePipe;
use Zend\Diactoros\Server as BaseServer;
use Zend\Stratigility\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class Server extends \Siphon\Foundation\Server implements MiddlewareInterface
{
    /**
     * URI path prefix
     *
     * @var string
     */
    protected $path = '/';

    /**
     * The middleware pipe instance
     *
     * @var \Zend\Stratigility\MiddlewarePipe
     */
    protected $middlewarePipe;

    /**
     * Listen to an incoming request
     *
     * @return void
     */
    public function listen()
    {
        BaseServer::createServer(
            $this,
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        )->listen();
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $app = $this->getApp();

        $middleware = $this->middleware($app);

        return $middleware($request, $response, $next);
    }

    /**
     * Get the middleware for the application
     *
     * @param \Siphon\Foundation\Application $app
     * @return \Zend\Stratigility\MiddlewareInterface
     */
    abstract protected function middleware(Application $app);

    /**
     * Attach middleware to the pipeline
     *
     * @param \Zend\Stratigility\MiddlewareInterface $middleware
     * @return \Zend\Stratigility\MiddlewarePipe
     */
    protected function pipe(MiddlewareInterface $middleware)
    {
        return $this->getMiddlewarePipe()->pipe($this->path, $middleware);
    }

    /**
     * Get the middleware middleware pipe instance
     *
     * @return \Zend\Stratigility\MiddlewarePipe
     */
    protected function getMiddlewarePipe()
    {
        if (is_null($this->middlewarePipe)) {
            $this->middlewarePipe = new MiddlewarePipe;
        }

        return $this->middlewarePipe;
    }
}