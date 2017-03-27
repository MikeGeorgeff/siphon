<?php

namespace Siphon\Http\Response;

use Siphon\View\Renderer;
use Zend\Diactoros\Response;
use Siphon\Http\Session\Session;
use Siphon\Http\Routing\Redirector;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\TextResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\EmptyResponse;

class Factory
{
    /**
     * @var \Siphon\Http\Routing\Redirector
     */
    protected $redirector;

    /**
     * @var \Siphon\Http\Session\Session
     */
    protected $session;

    /**
     * @var \Siphon\View\Renderer
     */
    protected $view;

    /**
     * @param \Siphon\Http\Routing\Redirector $redirector
     */
    public function __construct(Redirector $redirector)
    {
        $this->redirector = $redirector;
    }

    /**
     * Create a new response
     *
     * @param \Psr\Http\Message\StreamInterface|string $body
     * @param int                                      $status
     * @param array                                    $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function response($body = 'php://memory', $status = 200, array $headers = [])
    {
        return new Response($body, $status, $headers);
    }

    /**
     * Create a new json response
     *
     * @param mixed  $data
     * @param int    $status
     * @param array  $headers
     * @param int    $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function json($data, $status = 200, array $headers = [], $options = JsonResponse::DEFAULT_JSON_FLAGS)
    {
        return new JsonResponse($data, $status, $headers, $options);
    }

    /**
     * Create a new html response
     *
     * @param \Psr\Http\Message\StreamInterface|string $html
     * @param int                                      $status
     * @param array                                    $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function html($html, $status = 200, array $headers = [])
    {
        return new HtmlResponse($html, $status, $headers);
    }

    /**
     * Create an html response for a rendered view
     *
     * @param string $view
     * @param array  $parameters
     * @param int    $status
     * @param array  $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function view($view, array $parameters = [], $status = 200, array $headers = [])
    {
        $html = $this->view->render($view, $parameters);

        return $this->html($html, $status, $headers);
    }

    /**
     * Create a new text response
     *
     * @param \Psr\Http\Message\StreamInterface|string $text
     * @param int                                      $status
     * @param array                                    $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function text($text, $status = 200, array $headers = [])
    {
        return new TextResponse($text, $status, $headers);
    }

    /**
     * Create a new empty response
     *
     * @param int   $status
     * @param array $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function empty($status = 204, array $headers = [])
    {
        return new EmptyResponse($status, $headers);
    }

    /**
     * Create a new redirect response
     *
     * @param \Psr\Http\Message\UriInterface|string $uri
     * @param int                                   $status
     * @param array                                 $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function redirect($uri, $status = 302, array $headers = [])
    {
        return $this->redirector->redirect($uri, $status, $headers);
    }

    /**
     * Create a new redirect response to the given path
     *
     * @param string $path
     * @param int    $status
     * @param array  $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function redirectTo($path, $status = 302, array $headers = [])
    {
        return $this->redirector->to($path, $status, $headers);
    }

    /**
     * Create a redirect response to the current location
     *
     * @param int   $status
     * @param array $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function redirectCurrent($status = 302, array $headers = [])
    {
        return $this->redirector->current($status, $headers);
    }

    /**
     * Create a redirect response to the previous location
     *
     * @param int    $status
     * @param array  $headers
     * @param string $fallback
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function redirectPrevious($status = 302, array $headers = [], $fallback = '/')
    {
        return $this->redirector->previous($status, $headers, $fallback);
    }

    /**
     * Create a redirect response to a route
     *
     * @param string $name
     * @param array  $parameters
     * @param int    $status
     * @param array  $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function redirectRoute($name, array $parameters = [], $status = 302, array $headers = [])
    {
        return $this->redirector->route($name, $parameters, $status, $headers);
    }

    /**
     * Flash data to the session
     *
     * @param string       $key
     * @param array|string $value
     * @return \Siphon\Http\Response\FactoryInterface
     */
    public function flash($key, $value)
    {
        $this->session->flash($key, $value);

        return $this;
    }

    /**
     * Flash an array of errors to the session
     *
     * @param array $errors
     * @return \Siphon\Http\Response\FactoryInterface
     */
    public function flashErrors(array $errors)
    {
        $this->session->flashErrors($errors);

        return $this;
    }

    /**
     * Flash an array of input to the session
     *
     * @param array $input
     * @return \Siphon\Http\Response\FactoryInterface
     */
    public function flashInput(array $input)
    {
        $this->session->flashInput($input);

        return $this;
    }

    /**
     * Get the session instance
     *
     * @return \Siphon\Http\Session\Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Set the session instance
     *
     * @param \Siphon\Http\Session\Session $session
     * @return \Siphon\Http\Response\Factory
     */
    public function setSession(Session $session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * Get the view renderer instance
     *
     * @return \Siphon\View\Renderer
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Set the view instance
     *
     * @param \Siphon\View\Renderer $view
     * @return \Siphon\Http\Response\Factory
     */
    public function setView(Renderer $view)
    {
        $this->view = $view;

        return $this;
    }
}