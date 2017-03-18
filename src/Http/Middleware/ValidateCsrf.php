<?php

namespace Siphon\Http\Middleware;

use Siphon\Http\Request\RequestTrait;
use Zend\Stratigility\MiddlewareInterface;
use Siphon\Http\Exception\TokenMismatchException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ValidateCsrf implements MiddlewareInterface
{
    use RequestTrait;

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        if ($this->isReading($request) || $this->tokensMatch($request)) {
            return $next($request, $response);
        }

        throw new TokenMismatchException;
    }

    /**
     * Determine if the incoming request is a read request
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return bool
     */
    protected function isReading($request)
    {
        return in_array($request->getMethod(), ['GET', 'HEAD', 'OPTIONS']);
    }

    /**
     * Check if the tokens match
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return bool
     */
    protected function tokensMatch($request)
    {
        $token = $this->getInput($request, 'csrf_token') ?: $request->getHeader('X-CSRF-Token');

        $session = $request->getAttribute('session');

        return is_string($token) && hash_equals($token, $session->csrfToken());
    }
}