<?php

namespace Siphon\Test\Bus\Fixture;

class TestHandler
{
    public function handle(Test $command)
    {
        return 'Hi '.$command->name;
    }
}