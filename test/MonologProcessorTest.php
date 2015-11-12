<?php

namespace PhpMiddlewareTest\ExecutionTime;

use PhpMiddleware\ExecutionTime\ExecutionTimeProviderInterface;
use PhpMiddleware\ExecutionTime\MonologProcessor;
use PHPUnit_Framework_TestCase;

class MonologProcessorTest extends PHPUnit_Framework_TestCase
{
    protected $processor;
    protected $provider;

    protected function setUp()
    {
        $this->provider = $this->getMock(ExecutionTimeProviderInterface::class);
        $this->processor = new MonologProcessor($this->provider);
    }

    public function testAddTimeToRecord()
    {
        $this->provider->expects($this->once())->method('getExecutionTime')->willReturn(1.5);
        $record = [
            'extra' => [],
        ];
        $result = call_user_func($this->processor, $record);

        $this->assertArrayHasKey('execution-time', $result['extra']);
        $this->assertSame(1.5, $result['extra']['execution-time']);
    }
}
