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

class Factory implements FactoryInterface
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
     * {@inheritdoc}
     */
    public function response($body = 'php://memory', $status = 200, array $headers = [])
    {
        return new Response($body, $status, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function json($data, $status = 200, array $headers = [], $options = JsonResponse::DEFAULT_JSON_FLAGS)
    {
        return new JsonResponse($data, $status, $headers, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function html($html, $status = 200, array $headers = [])
    {
        return new HtmlResponse($html, $status, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function view($view, array $parameters = [], $status = 200, array $headers = [])
    {
        $html = $this->view->render($view, $parameters);

        return $this->html($html, $status, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function text($text, $status = 200, array $headers = [])
    {
        return new TextResponse($text, $status, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function empty($status = 204, array $headers = [])
    {
        return new EmptyResponse($status, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function redirect($uri, $status = 302, array $headers = [])
    {
        return $this->redirector->redirect($uri, $status, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function redirectTo($path, $status = 302, array $headers = [])
    {
        return $this->redirector->to($path, $status, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function redirectCurrent($status = 302, array $headers = [])
    {
        return $this->redirector->current($status, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function redirectPrevious($status = 302, array $headers = [], $fallback = '/')
    {
        return $this->redirector->previous($status, $headers, $fallback);
    }

    /**
     * {@inheritdoc}
     */
    public function redirectRoute($name, array $parameters = [], $status = 302, array $headers = [])
    {
        return $this->redirector->route($name, $parameters, $status, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function flash($key, $value)
    {
        $this->session->flash($key, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function flashErrors(array $errors)
    {
        $this->session->flashErrors($errors);

        return $this;
    }

    /**
     * {@inheritdoc}
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