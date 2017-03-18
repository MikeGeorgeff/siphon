<?php

namespace Siphon\View\Extension;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Siphon\Http\Session\Session as SiphonSession;

class Session implements ExtensionInterface
{
    /**
     * @var \Siphon\Http\Session\Session
     */
    protected $session;

    /**
     * @param \Siphon\Http\Session\Session $session
     */
    public function __construct(SiphonSession $session)
    {
        $this->session = $session;
    }

    /**
     * Register view functions
     *
     * @param \League\Plates\Engine $engine
     * @return void
     */
    public function register(Engine $engine)
    {
        $engine->registerFunction('session', [$this, 'getObject']);
    }

    /**
     * @return \Siphon\View\Extension\Session
     */
    public function getObject()
    {
        return $this;
    }

    /**
     * Get an item from the session
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->session->get($key);
    }

    /**
     * Determine if the session has a flash key
     *
     * @param string $key
     * @return bool
     */
    public function hasFlash($key)
    {
        return $this->session->hasFlash($key);
    }

    /**
     * Get an item from the session flash array
     *
     * @param string $key
     * @return array
     */
    public function getFlash($key)
    {
        return $this->session->getFlash($key);
    }

    /**
     * Get flash input from the session
     *
     * @param string|null $key
     * @return mixed
     */
    public function input($key = null)
    {
        return $this->session->input($key);
    }

    /**
     * Determine if the session has flashed errors
     *
     * @return bool
     */
    public function hasErrors()
    {
        return ! empty($this->session->errors());
    }

    /**
     * Get flash errors from the session
     *
     * @param string|null $key
     * @return mixed
     */
    public function errors($key = null)
    {
        return $this->session->errors($key);
    }

    /**
     * Get the csrf token from the session
     *
     * @return string
     */
    public function csrfToken()
    {
        return $this->session->csrfToken();
    }
}