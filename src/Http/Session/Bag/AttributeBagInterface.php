<?php

namespace Siphon\Http\Session\Bag;

interface AttributeBagInterface extends SessionBagInterface
{
    /**
     * Determine if the key exists in the bag
     *
     * @param string $key
     * @return bool
     */
    public function has($key);

    /**
     * Get an item from the bag
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Get an item from the bag and remove it from the stack
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function pull($key, $default = null);

    /**
     * Return all attributes
     *
     * @return array
     */
    public function all();

    /**
     * Set an attribute
     *
     * @param string $key
     * @param mixed  $value
     * @return void
     */
    public function set($key, $value);

    /**
     * Remove an item from the stack
     *
     * @param string $key
     * @return bool
     */
    public function remove($key);
}