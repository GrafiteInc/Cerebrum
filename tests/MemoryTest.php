<?php

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Yab\Cerebrum\Memory;

/**
 * Test Class.
 */
class TestClass
{
    use Memory;

    public function __construct()
    {
        $this->memoryDuration = 60;
        $this->forgetful = [
            'all',
            'findById',
        ];
    }

    public function all()
    {
        return ['one'];
    }

    public function findById()
    {
        return ['one'];
    }

    public function testMethod()
    {
        return ['one', 'two'];
    }

    public function testMethodWithArgs()
    {
        return ['one', 'two'];
    }
}

class MemoryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $app = new Container();
        $app->singleton('app', Illuminate\Container\Container::class);
        Facade::setFacadeApplication($app);

        $cache = Mockery::mock('cache')
            ->shouldReceive('has')
            ->withAnyArgs()
            ->andReturn([])
            ->shouldReceive('get')
            ->withAnyArgs()
            ->andReturn([])
            ->shouldReceive('forget')
            ->withAnyArgs()
            ->andReturn([])
            ->shouldReceive('put')
            ->withAnyArgs()
            ->andReturn([])
            ->getMock();

        $app->instance('cache', $cache);

        $this->testClass = new TestClass();
    }

    public function testMemory()
    {
        $test = $this->testClass->remember(['test']);
        $this->assertEquals($test, ['test']);
        $this->assertTrue(is_array($test));
    }

    public function testForget()
    {
        $test = $this->testClass->forget(1);
        $this->assertEquals($test, $this->testClass);
    }

    public function testGetKey()
    {
        $test = $this->testClass->forget();
        $this->assertEquals($test, $this->testClass);
    }
}
