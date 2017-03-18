<?php

namespace Siphon\Http\Session\Bag;

class AttributeBag implements AttributeBagInterface
{
    /**
     * The bag name
     *
     * @var string
     */
    protected $name = 'attributes';

    /**
     * The bag storage key
     *
     * @var string
     */
    protected $storageKey;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @param string $storageKey
     */
    public function __construct($storageKey = '_session.attributes')
    {
        $this->storageKey = $storageKey;
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return array_key_exists($key, $this->attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        return $this->has($key) ? $this->attributes[$key] : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function pull($key, $default = null)
    {
        if (! $this->has($key)) {
            return $default;
        }

        $return = $this->attributes[$key];

        unset($this->attributes[$key]);

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        if (! $this->has($key)) {
            return false;
        }

        unset($this->attributes[$key]);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $return = $this->all();

        $this->attributes = [];

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getStorageKey()
    {
        return $this->storageKey;
    }
}