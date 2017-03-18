<?php

namespace Siphon\Http\Middleware;

use Siphon\Http\Cookie\Factory;
use Dflydev\FigCookies\FigResponseCookies;
use Zend\Stratigility\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddQueuedCookiesToResponse implements MiddlewareInterface
{
    /**
     * @var \Siphon\Http\Cookie\Factory
     */
    protected $cookie;

    /**
     * @param \Siphon\Http\Cookie\Factory $cookie
     */
    public function __construct(Factory $cookie)
    {
        $this->cookie = $cookie;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $response = $next($request, $response);

        foreach ($this->cookie->getQueuedCookies() as $cookie) {
            $response = FigResponseCookies::set($response, $cookie);
        }

        return $response;
    }
}