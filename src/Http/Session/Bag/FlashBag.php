<?php

namespace Siphon\Http\Session\Bag;

class FlashBag implements FlashBagInterface
{
    /**
     * The name of the bag
     *
     * @var string
     */
    protected $name = 'flashes';

    /**
     * The bag storage key
     *
     * @var string
     */
    protected $storageKey;

    /**
     * The flash messages
     *
     * @var array
     */
    protected $attributes = ['current' => [], 'new' => []];

    /**
     * @param string $storageKey
     */
    public function __construct($storageKey = '_session.flashes')
    {
        $this->storageKey = $storageKey;
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(array $attributes)
    {
        $this->attributes = $attributes;

        $this->attributes['current'] =
            array_key_exists('new', $this->attributes) ? $this->attributes['new'] : [];

        $this->attributes['new'] = [];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return array_key_exists($key, $this->attributes['current']) && $this->attributes['current'][$key];
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = [])
    {
        return $this->has($key) ? $this->attributes['current'][$key] : (array) $default;
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->attributes['current'];
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $this->attributes['new'][$key] = (array) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $return = $this->attributes;

        $this->attributes = ['current' => [], 'new' => []];

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

    /**
     * Get the full flash attributes array
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}