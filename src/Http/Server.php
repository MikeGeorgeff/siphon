<?php

namespace Siphon\Http;

use Siphon\Foundation\Application;
use Zend\Stratigility\MiddlewarePipe;
use Zend\Stratigility\NoopFinalHandler;
use Zend\Diactoros\Server as BaseServer;
use Zend\Stratigility\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class Server extends \Siphon\Foundation\Server
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
        )->listen(new NoopFinalHandler);
    }

    /**
     * Use as psr-7 middleware
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param callable|null                            $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        $app = $this->getApp();

        $this->middleware($app);

        $middleware = $this->middlewarePipe;

        return $middleware($request, $response, $next);
    }

    /**
     * Get the middleware for the application
     *
     * @param \Siphon\Foundation\Application $app
     * @return void
     */
    abstract protected function middleware(Application $app);

    /**
     * Register service providers
     *
     * @param \Siphon\Foundation\Application $app
     * @return void
     */
    protected function providers(Application $app)
    {
        $app->register(\Siphon\Foundation\Provider\LogServiceProvider::class);
        $app->register(\Siphon\Bus\Provider\BusServiceProvider::class);
        $app->register(\Siphon\Cache\CacheServiceProvider::class);
        $app->register(\Siphon\Database\DatabaseServiceProvider::class);
        $app->register(\Siphon\Foundation\Provider\EncryptionServiceProvider::class);
        $app->register(\Siphon\Debug\DebugServiceProvider::class);
        $app->register(\Siphon\Foundation\Provider\FilesystemServiceProvider::class);
        $app->register(\Siphon\Foundation\Provider\HashServiceProvider::class);
        $app->register(\Siphon\Http\Cookie\CookieServiceProvider::class);
        $app->register(\Siphon\Http\Response\ResponseServiceProvider::class);
        $app->register(\Siphon\Http\Routing\RoutingServiceProvider::class);
        $app->register(\Siphon\Http\Session\SessionServiceProvider::class);
        $app->register(\Siphon\Redis\RedisServiceProvider::class);
        $app->register(\Siphon\View\ViewServiceProvider::class);
    }

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

            $this->middlewarePipe->setResponsePrototype(new \Zend\Diactoros\Response);
        }

        return $this->middlewarePipe;
    }
}