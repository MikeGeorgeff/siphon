<?php

namespace Siphon\Http\Response;

interface FactoryInterface
{
    /**
     * Create a new response
     *
     * @param \Psr\Http\Message\StreamInterface|string $body
     * @param int                                      $status
     * @param array                                    $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function response($body = 'php://memory', $status = 200, array $headers = []);

    /**
     * Create a new json response
     *
     * @param string $data
     * @param int    $status
     * @param array  $headers
     * @param int    $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function json($data, $status = 200, array $headers = [], $options = JsonResponse::DEFAULT_JSON_FLAGS);

    /**
     * Create a new html response
     *
     * @param \Psr\Http\Message\StreamInterface|string $html
     * @param int                                      $status
     * @param array                                    $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function html($html, $status = 200, array $headers = []);

    /**
     * Create an html response for a rendered view
     *
     * @param string $view
     * @param array  $parameters
     * @param int    $status
     * @param array  $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function view($view, array $parameters = [], $status = 200, array $headers = []);

    /**
     * Create a new text response
     *
     * @param \Psr\Http\Message\StreamInterface|string $text
     * @param int                                      $status
     * @param array                                    $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function text($text, $status = 200, array $headers = []);

    /**
     * Create a new empty response
     *
     * @param int   $status
     * @param array $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function empty($status = 204, array $headers = []);

    /**
     * Create a new redirect response
     *
     * @param \Psr\Http\Message\UriInterface|string $uri
     * @param int                                   $status
     * @param array                                 $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function redirect($uri, $status = 302, array $headers = []);

    /**
     * Create a new redirect response to the given path
     *
     * @param string $path
     * @param int    $status
     * @param array  $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function redirectTo($path, $status, array $headers = []);

    /**
     * Create a redirect response to the current location
     *
     * @param int   $status
     * @param array $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function redirectCurrent($status = 302, array $headers = []);

    /**
     * Create a redirect response to the previous location
     *
     * @param int    $status
     * @param array  $headers
     * @param string $fallback
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function redirectPrevious($status = 302, array $headers = [], $fallback = '/');

    /**
     * Create a redirect response to a route
     *
     * @param string $name
     * @param array  $parameters
     * @param int    $status
     * @param array  $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function redirectRoute($name, array $parameters = [], $status = 302, array $headers = []);

    /**
     * Flash data to the session
     *
     * @param string       $key
     * @param array|string $value
     * @return \Siphon\Http\Response\FactoryInterface
     */
    public function flash($key, $value);

    /**
     * Flash an array of errors to the session
     *
     * @param array $errors
     * @return \Siphon\Http\Response\FactoryInterface
     */
    public function flashErrors(array $errors);

    /**
     * Flash an array of input to the session
     *
     * @param array $input
     * @return \Siphon\Http\Response\FactoryInterface
     */
    public function flashInput(array $input);
}