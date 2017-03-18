<?php

namespace Siphon\Test\Bus\Fixture;

class Test
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}