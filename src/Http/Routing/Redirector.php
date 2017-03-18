<?php

namespace Siphon\Http\Routing;

use Zend\Diactoros\Response\RedirectResponse;

class Redirector
{
    /**
     * The url generator instance
     *
     * @var \Siphon\Http\Routing\UrlGenerator
     */
    protected $url;

    /**
     * @param \Siphon\Http\Routing\UrlGenerator $url
     */
    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    /**
     * Create a redirect response to the given path
     *
     * @param string $path
     * @param int    $status
     * @param array  $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function to($path, $status = 302, array $headers = [])
    {
        $uri = $this->url->to($path);

        return $this->redirect($uri, $status, $headers);
    }

    /**
     * Create a redirect response to the current location
     *
     * @param int   $status
     * @param array $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function current($status = 302, array $headers = [])
    {
        return $this->redirect($this->url->current(), $status, $headers);
    }

    /**
     * Create a redirect response to the previous location
     *
     * @param int    $status
     * @param array  $headers
     * @param string $fallback
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function previous($status = 302, array $headers = [], $fallback = '/')
    {
        $uri = $this->url->previous($fallback);

        return $this->redirect($uri, $status, $headers);
    }

    /**
     * Create a redirect response to a named route
     *
     * @param string $name
     * @param array  $parameters
     * @param int    $status
     * @param array  $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function route($name, array $parameters = [], $status = 302, array $headers = [])
    {
        $uri = $this->url->route($name, $parameters);

        return $this->redirect($uri, $status, $headers);
    }

    /**
     * Create a redirect response
     *
     * @param \Psr\Http\Message\UriInterface|string $uri
     * @param int                                   $status
     * @param array                                 $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function redirect($uri, $status = 302, array $headers = [])
    {
        return new RedirectResponse($uri, $status, $headers);
    }

    /**
     * Get the url generator instance
     *
     * @return \Siphon\Http\Routing\UrlGenerator
     */
    public function getUrlGenerator()
    {
        return $this->url;
    }
}