<?php

namespace Siphon\Test\View;

use League\Plates\Engine;
use Siphon\View\Extension\Url;
use Siphon\Http\Routing\UrlGenerator;

class UrlExtensionTest extends \Siphon\Test\TestCase
{
    public function testRegister()
    {
        $engine = $this->mock(Engine::class);

        $ext = new Url($this->mock(UrlGenerator::class));

        $engine->shouldReceive('registerFunction')->twice();

        $ext->register($engine);
    }
}