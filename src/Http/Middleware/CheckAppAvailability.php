<?php

namespace Siphon\Http\Middleware;

use Siphon\Foundation\Application;
use Siphon\Http\Event\DownForMaintenance;
use Zend\Diactoros\Response\TextResponse;
use Zend\Stratigility\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CheckAppAvailability implements MiddlewareInterface
{
    /**
     * @var \Siphon\Foundation\Application
     */
    protected $app;

    /**
     * @param \Siphon\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        if (file_exists($this->app->storagePath().'/framework/down')) {
            $this->app['events']->dispatch(new DownForMaintenance($request));

            return new TextResponse('The application is currently down for maintenance.', 503);
        }

        return $next($request, $response);
    }
}