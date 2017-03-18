<?php

namespace Siphon\Http\Session\Bag;

interface SessionBagInterface
{
    /**
     * Initialize the bag
     *
     * @param array $attributes
     * @return \Siphon\Http\Session\Bag\SessionBagInterface
     */
    public function initialize(array $attributes);

    /**
     * The bag name
     *
     * @return string
     */
    public function getName();

    /**
     * Get the bag storage key
     *
     * @return string
     */
    public function getStorageKey();

    /**
     * Clear the contents of the bag
     *
     * @return array The contents of the bag
     */
    public function clear();
}