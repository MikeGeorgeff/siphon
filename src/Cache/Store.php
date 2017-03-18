<?php

namespace Siphon\Cache;

interface Store
{
    /**
     * Determine if the given key exists in the cache
     *
     * @param string $key
     * @return bool
     */
    public function exists($key);

    /**
     * Get an item from the cache
     *
     * @param string $key
     * @return mixed
     */
    public function get($key);

    /**
     * Save an item on the cache for the given number of minutes
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $minutes
     * @return void
     */
    public function save($key, $value, $minutes);

    /**
     * Save a non expiring item on the cache
     *
     * @param string $key
     * @param mixed  $value
     * @return void
     */
    public function forever($key, $value);

    /**
     * Remove an item from the cache
     *
     * @param string $key
     * @return bool
     */
    public function remove($key);

    /**
     * Flush all items from the cache
     *
     * @return void
     */
    public function flush();
}