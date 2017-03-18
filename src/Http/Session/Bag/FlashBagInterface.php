<?php

namespace Siphon\Http\Session\Bag;

interface FlashBagInterface extends SessionBagInterface
{
    /**
     * Determine if messages exist for the given key
     *
     * @param string $key
     * @return bool
     */
    public function has($key);

    /**
     * Get flash messages for the given key and remove it from the stack
     *
     * @param string        $key
     * @param array|string  $default
     * @return array
     */
    public function get($key, $default = []);

    /**
     * Get all flash messages and clear the stack
     *
     * @return array
     */
    public function all();

    /**
     * Set flash messages
     *
     * @param string       $key
     * @param array|string $value
     * @return void
     */
    public function set($key, $value);
}