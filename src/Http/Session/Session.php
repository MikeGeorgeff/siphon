<?php

namespace Siphon\Http\Session;

use Illuminate\Support\Str;
use SessionHandlerInterface;
use Siphon\Http\Session\Bag\FlashBag;
use Siphon\Http\Session\Bag\AttributeBag;
use Siphon\Http\Session\Bag\SessionBagInterface;

class Session
{
    /**
     * The session id
     *
     * @var string
     */
    protected $id;

    /**
     * The session name
     *
     * @var string
     */
    protected $name = 'siphon_session';

    /**
     * The session handler
     *
     * @var \SessionHandlerInterface
     */
    protected $handler;

    /**
     * Array of session bag instances
     *
     * @var \Siphon\Http\Session\Bag\SessionBagInterface[]
     */
    protected $bags;

    /**
     * Determine if the session has been started
     *
     * @var bool
     */
    protected $started = false;

    /**
     * @param \SessionHandlerInterface $handler
     */
    public function __construct(SessionHandlerInterface $handler)
    {
        $this->handler = $handler;

        $this->registerBag(new AttributeBag);
        $this->registerBag(new FlashBag);
    }

    /**
     * Start the session
     *
     * @return bool
     */
    public function start()
    {
        if ($this->isStarted()) {
            return true;
        }

        $this->loadSession();

        $this->generateCsrfToken();

        return $this->started = true;
    }

    /**
     * Load the session data from the handler
     *
     * @return void
     */
    protected function loadSession()
    {
        $session = unserialize($this->handler->read($this->getId()));

        foreach ($this->bags as $bag) {
            $key = $bag->getStorageKey();

            $data = isset($session[$key]) ? $session[$key] : [];

            $bag->initialize($data);
        }
    }

    /**
     * Save the session data to storage
     *
     * @return void
     */
    public function save()
    {
        $session = [];

        foreach ($this->bags as $bag) {
            $session[$bag->getStorageKey()] = $bag->clear();
        }

        $this->handler->write($this->getId(), serialize($session));

        $this->started = false;
    }

    /**
     * Determine if the key exists
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return $this->getAttributesBag()->has($key);
    }

    /**
     * Set an item on the session
     *
     * @param string $key
     * @param mixed  $value
     * @return void
     */
    public function set($key, $value)
    {
        $this->getAttributesBag()->set($key, $value);
    }

    /**
     * Return all the items stored in the session
     *
     * @return array
     */
    public function all()
    {
        return $this->getAttributesBag()->all();
    }

    /**
     * Get an item from the session
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->getAttributesBag()->get($key, $default);
    }

    /**
     * Get an item from the session and remove it from storage
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function pull($key, $default = null)
    {
        return $this->getAttributesBag()->pull($key, $default);
    }

    /**
     * Remove an item from the session
     *
     * @param string $key
     * @return bool
     */
    public function remove($key)
    {
        return $this->getAttributesBag()->remove($key);
    }

    /**
     * Flash an item onto the session
     *
     * @param string       $key
     * @param array|string $value
     * @return void
     */
    public function flash($key, $value)
    {
        $this->getFlashBag()->set($key, $value);
    }

    /**
     * Determine if the given key was flashed to the session
     *
     * @param string $key
     * @return bool
     */
    public function hasFlash($key)
    {
        return $this->getFlashBag()->has($key);
    }

    /**
     * Get all flashed items
     *
     * @return array
     */
    public function allFlash()
    {
        return $this->getFlashBag()->all();
    }

    /**
     * Get a flashed item
     *
     * @param string       $key
     * @param array|string $default
     * @return array
     */
    public function getFlash($key, $default = [])
    {
        return $this->getFlashBag()->get($key, $default);
    }

    /**
     * Flash input into the session
     *
     * @param array $input
     * @return void
     */
    public function flashInput(array $input)
    {
        $this->flash('_input', $input);
    }

    /**
     * Flash errors onto the session
     *
     * @param array $errors
     * @return void
     */
    public function flashErrors(array $errors)
    {
        $this->flash('_errors', $errors);
    }

    /**
     * Get flashed input from the session
     *
     * @param string|null $key
     * @return mixed
     */
    public function input($key = null)
    {
        $return = $this->getFlash('_input', []);

        if (! is_null($key)) {
            $return = array_key_exists($key, $return) ? $return[$key] : null;
        }

        return $return;
    }

    /**
     * Get flashed errors from the session
     *
     * @param string|null $key
     * @return mixed
     */
    public function errors($key = null)
    {
        $return = $this->getFlash('_errors', []);

        if (! is_null($key)) {
            $return = array_key_exists($key, $return) ? $return[$key] : null;
        }

        return $return;
    }

    /**
     * Remove all data from the session
     *
     * @return void
     */
    public function flush()
    {
        foreach ($this->bags as $bag) {
            $bag->clear();
        }
    }

    /**
     * Flush the session and regenerate the session id
     *
     * @return bool
     */
    public function invalidate()
    {
        $this->flush();

        return $this->migrate(true);
    }

    /**
     * Generate a new id for the session
     *
     * @param bool $destroy
     * @return bool
     */
    public function migrate($destroy = false)
    {
        if ($destroy) {
            $this->handler->destroy($this->getId());
        }

        $this->setId();

        return true;
    }

    /**
     * Determine if the session has been started
     *
     * @return bool
     */
    public function isStarted()
    {
        return $this->started;
    }

    /**
     * Get the attributes bag
     *
     * @return \Siphon\Http\Session\Bag\AttributeBagInterface
     */
    public function getAttributesBag()
    {
        return $this->getBag('attributes');
    }

    /**
     * Get the flash bag
     *
     * @return \Siphon\Http\Session\Bag\FlashBagInterface
     */
    public function getFlashBag()
    {
        return $this->getBag('flashes');
    }

    /**
     * Get a registered session bag
     *
     * @param string $name
     * @return \Siphon\Http\Session\Bag\SessionBagInterface
     *
     * @throws \InvalidArgumentException
     */
    public function getBag($name)
    {
        if (! isset($this->bags[$name])) {
            throw new \InvalidArgumentException(sprintf(
                'The bag named %s is not registered.', $name
            ));
        }

        return $this->bags[$name];
    }

    /**
     * Register a session bag
     *
     * @param \Siphon\Http\Session\Bag\SessionBagInterface $bag
     * @return void
     *
     * @throws \RuntimeException
     */
    public function registerBag(SessionBagInterface $bag)
    {
        if ($this->isStarted()) {
            throw new \RuntimeException('Cannot register a bag after the session has been started.');
        }

        $this->bags[$bag->getName()] = $bag;
    }

    /**
     * Get the CSRF token
     *
     * @return string
     */
    public function csrfToken()
    {
        return $this->get('_token');
    }

    /**
     * Generate a CSRF token
     *
     * @return void
     */
    public function generateCsrfToken()
    {
        if (! $this->has('_token')) {
            $this->set('_token', Str::random(40));
        }
    }

    /**
     * Get the session id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the session id
     *
     * @param string|null $id
     * @return void
     */
    public function setId($id = null)
    {
        $this->id = $this->isValidId($id) ? $id : Str::random(40);
    }

    /**
     * Determine if the given id is valid
     *
     * @param string $id
     * @return bool
     */
    public function isValidId($id)
    {
        return is_string($id) && ctype_alnum($id) && strlen($id) === 40;
    }

    /**
     * Get the session name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the session name
     *
     * @param string $name
     * @return void
     *
     * @throws \RuntimeException
     */
    public function setName($name)
    {
        if ($this->isStarted()) {
            throw new \RuntimeException('Cannot set the name once the session has started.');
        }

        $this->name = $name;
    }

    /**
     * Get the session handler
     *
     * @return \SessionHandlerInterface
     */
    public function getHandler()
    {
        return $this->handler;
    }
}