<?php

namespace Siphon\Test\View;

use League\Plates\Engine;
use Siphon\View\PlatesRenderer;

class PlatesRendererTest extends \Siphon\Test\TestCase
{
    /**
     * @var \Mockery\MockInterface
     */
    protected $engine;

    public function testRender()
    {
        $view = $this->view();

        $this->engine->shouldReceive('render')->once()->andReturn('foo');

        $this->assertEquals('foo', $view->render('template'));
    }

    public function testAddPath()
    {
        $view = $this->view();

        $this->engine->shouldReceive('addFolder')->once();

        $view->addPath('foo', '/path/to/folder');
    }

    public function testShareData()
    {
        $view = $this->view();

        $this->engine->shouldReceive('addData')->once();

        $view->shareData('foo', 'bar');
    }

    protected function view()
    {
        $this->engine = $this->mock(Engine::class);

        return new PlatesRenderer($this->engine);
    }

    protected function after()
    {
        $this->engine = null;
    }
}