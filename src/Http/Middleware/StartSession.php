<?php

namespace Siphon\Http\Middleware;

use Siphon\Http\Cookie\Factory;
use Siphon\Http\Session\Session;
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Zend\Stratigility\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class StartSession implements MiddlewareInterface
{
    /**
     * @var \Siphon\Http\Session\Session
     */
    protected $session;

    /**
     * @var \Siphon\Http\Cookie\Factory
     */
    protected $cookie;

    /**
     * The session lifetime (in minutes)
     *
     * @var int
     */
    protected $lifetime;

    /**
     * @param \Siphon\Http\Session\Session      $session
     * @param \Siphon\Http\Cookie\Factory $cookie
     * @param int                               $lifetime
     */
    public function __construct(Session $session, Factory $cookie, $lifetime)
    {
        $this->session  = $session;
        $this->cookie   = $cookie;
        $this->lifetime = $lifetime;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $session = $this->startSession($request);

        $request = $request->withAttribute('session', $session);

        $response = $next($request, $response);

        $this->session->save();

        return $this->prepareResponse($response);
    }

    /**
     * Start the session
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Siphon\Http\Session\Session
     */
    protected function startSession(Request $request)
    {
        $cookie = FigRequestCookies::get($request, $this->session->getName());

        $this->session->setId($cookie->getValue());

        $this->session->start();

        return $this->session;
    }

    /**
     * Prepare the response for sending
     *
     * @param \Psr\Http\Message\ResponseInterface  $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function prepareResponse(Response $response)
    {
        $cookie = $this->cookie->make(
            $this->session->getName(), $this->session->getId(), $this->lifetime
        );

        $response = FigResponseCookies::set($response, $cookie);

        return $response->withHeader('X-CSRF-Token', $this->session->csrfToken());
    }
}