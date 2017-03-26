<?php

namespace Siphon\Cache;

use Closure;

class Repository
{
    /**
     * @var \Siphon\Cache\Store
     */
    protected $store;

    /**
     * String to prepend to the cache keys
     *
     * @var string
     */
    protected $prefix = '';

    /**
     * @param \Siphon\Cache\Store $store
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * Determine if the given key exists in the cache
     *
     * @param string $key
     * @return bool
     */
    public function exists($key)
    {
        return $this->store->exists($this->prefix.$key);
    }

    /**
     * Get an item from the cache by it's key
     *
     * @param string     $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $value = $this->store->get($this->prefix.$key);

        if (is_null($value)) {
            $value = $default;
        }

        return $value;
    }

    /**
     * Get an item from the cache and remove it
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function pull($key, $default = null)
    {
        $value = $this->get($key, $default);

        $this->remove($key);

        return $value;
    }

    /**
     * Save an item in the cache
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $minutes
     * @param bool   $overwrite
     * @return bool
     */
    public function save($key, $value, $minutes, $overwrite = true)
    {
        if (! $overwrite && $this->exists($key)) {
            return false;
        }

        $this->store->save($this->prefix.$key, $value, $minutes);

        return true;
    }

    /**
     * Save an item in the cache indefinitely
     *
     * @param string $key
     * @param mixed  $value
     * @return void
     */
    public function forever($key, $value)
    {
        $this->store->forever($this->prefix.$key, $value);
    }

    /**
     * Get an item from the cache or save the value of the callback
     *
     * @param string   $key
     * @param int      $minutes
     * @param \Closure $callback
     * @return mixed
     */
    public function remember($key, $minutes, Closure $callback)
    {
        if (! is_null($value = $this->get($key))) {
            return $value;
        }

        $this->save($key, $value = $callback(), $minutes);

        return $value;
    }

    /**
     * Get an item from the cache or save the callback indefinitely
     *
     * @param string   $key
     * @param \Closure $callback
     * @return mixed
     */
    public function rememberForever($key, Closure $callback)
    {
        if (! is_null($value = $this->get($key))) {
            return $value;
        }

        $this->forever($key, $value = $callback());

        return $value;
    }

    /**
     * Remove an item from the cache
     *
     * @param string $key
     * @return bool
     */
    public function remove($key)
    {
        return $this->store->remove($this->prefix.$key);
    }

    /**
     * Clear the cache
     *
     * @return void
     */
    public function flush()
    {
        $this->store->flush();
    }

    /**
     * Set the storage connection
     * 
     * @param string $connection
     * @return \Siphon\Cache\Repository
     */
    public function setConnection($connection)
    {
        $this->store->setConnection($connection);

        return $this;
    }

    /**
     * Get the store instance
     *
     * @return \Siphon\Cache\Store
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * Get the key prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Set the key prefix
     *
     * @param string $value
     * @return \Siphon\Cache\Repository
     */
    public function setPrefix($value)
    {
        $this->prefix = ! empty($value) ? $value.':' : '';

        return $this;
    }
}