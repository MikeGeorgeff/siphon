<?php

namespace Siphon\Test;

use Mockery;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Any functionality to run before each test
     *
     * @return void
     */
    protected function before()
    {
    }

    /**
     * Any functionality to run after each test
     *
     * @return void
     */
    protected function after()
    {
    }

    /**
     * Create a mock object
     *
     * @return \Mockery\MockInterface
     */
    protected function mock()
    {
        $args = func_get_args();

        return call_user_func_array([Mockery::getContainer(), 'mock'], $args);
    }

    /**
     * Setup the test environment
     *
     * @return void
     */
    protected function setUp()
    {
        $this->before();
    }

    /**
     * Cleanup the test environment
     *
     * @return void
     */
    protected function tearDown()
    {
        Mockery::close();

        $this->after();
    }
}