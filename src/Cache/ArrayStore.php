<?php

namespace Siphon\Cache;

class ArrayStore implements Store
{
    /**
     * Array of cached items
     *
     * @var array
     */
    protected $cache = [];

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return array_key_exists($key, $this->cache);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        if ($this->exists($key)) {
            return $this->cache[$key];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save($key, $value, $minutes)
    {
        $this->cache[$key] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function forever($key, $value)
    {
        $this->save($key, $value, 0);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        unset($this->cache[$key]);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        $this->cache = [];
    }

    /**
     * {@inheritdoc}
     */
    public function setConnection($connection)
    {
        return $this;
    }
}