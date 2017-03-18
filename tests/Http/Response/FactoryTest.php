<?php

namespace Siphon\Test\Http\Response;

use Siphon\View\Renderer;
use Zend\Diactoros\Response;
use Siphon\Http\Session\Session;
use Siphon\Http\Response\Factory;
use Siphon\Http\Routing\Redirector;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\TextResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\RedirectResponse;

class FactoryTest extends \Siphon\Test\TestCase
{
    /**
     * @var \Mockery\MockInterface
     */
    protected $redirect;

    /**
     * @var \Mockery\MockInterface
     */
    protected $session;

    /**
     * @var \Mockery\MockInterface
     */
    protected $view;

    public function testResponse()
    {
        $factory = $this->factory();

        $response = $factory->response();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(Response::class, $response);
    }

    public function testJson()
    {
        $factory = $this->factory();

        $response = $factory->json('{"foo":"bar"');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function testHtml()
    {
        $factory = $this->factory();

        $response = $factory->html('<h1>Test</h1>');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(HtmlResponse::class, $response);
    }

    public function testView()
    {
        $factory = $this->factory();

        $this->view->shouldReceive('render')->once()->andReturn('<h1>Test</h1>');

        $response = $factory->view('foo');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(HtmlResponse::class, $response);
    }

    public function testText()
    {
        $factory = $this->factory();

        $response = $factory->text('foo');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(TextResponse::class, $response);
    }

    public function testEmpty()
    {
        $factory = $this->factory();

        $response = $factory->empty();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertInstanceOf(EmptyResponse::class, $response);
    }

    public function testRedirect()
    {
        $factory = $this->factory();

        $this->redirect->shouldReceive('redirect')->once()->andReturn(new RedirectResponse('http://foo.com'));

        $response = $factory->redirect('http://foo.com');

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testRedirectTo()
    {
        $factory = $this->factory();

        $this->redirect->shouldReceive('to')->once()->andReturn(new RedirectResponse('http://foo.com'));

        $response = $factory->redirectTo('bar');

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testRedirectCurrent()
    {
        $factory = $this->factory();

        $this->redirect->shouldReceive('current')->once()->andReturn(new RedirectResponse('http://foo.com'));

        $response = $factory->redirectCurrent();

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testRedirectPrevious()
    {
        $factory = $this->factory();

        $this->redirect->shouldReceive('previous')->once()->andReturn(new RedirectResponse('http://foo.com'));

        $response = $factory->redirectPrevious();

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testRedirectRoute()
    {
        $factory = $this->factory();

        $this->redirect->shouldReceive('route')->once()->andReturn(new RedirectResponse('http://foo.com'));

        $response = $factory->redirectRoute('home');

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testFlash()
    {
        $factory = $this->factory();

        $this->session->shouldReceive('flash')->once();

        $this->assertInstanceOf(Factory::class, $factory->flash('key', 'value'));
    }

    public function testFlashErrors()
    {
        $factory = $this->factory();

        $this->session->shouldReceive('flashErrors')->once();

        $this->assertInstanceOf(Factory::class, $factory->flashErrors([]));
    }

    public function testFlashInput()
    {
        $factory = $this->factory();

        $this->session->shouldReceive('flashInput')->once();

        $this->assertInstanceOf(Factory::class, $factory->flashInput([]));
    }

    protected function factory()
    {
        $this->redirect = $this->mock(Redirector::class);
        $this->session  = $this->mock(Session::class);
        $this->view     = $this->mock(Renderer::class);

        $factory = new Factory($this->redirect);

        $factory->setSession($this->session);
        $factory->setView($this->view);

        return $factory;
    }

    protected function after()
    {
        $this->redirect = null;
        $this->session  = null;
        $this->view     = null;
    }
}