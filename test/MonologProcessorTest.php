<?php

namespace PhpMiddlewareTest\ResponseTime;

use PhpMiddleware\ResponseTime\MonologProcessor;
use PhpMiddleware\ResponseTime\ResponseTimeProviderInterface;
use PHPUnit_Framework_TestCase;

class MonologProcessorTest extends PHPUnit_Framework_TestCase
{
    protected $processor;
    protected $responseTimeProvider;

    protected function setUp()
    {
        $this->responseTimeProvider = $this->getMock(ResponseTimeProviderInterface::class);
        $this->processor = new MonologProcessor($this->responseTimeProvider);
    }

    public function testAddTimeToRecord()
    {
        $this->responseTimeProvider->expects($this->once())->method('getExcecutionTime')->willReturn(1.5);
        $record = [
            'extra' => [],
        ];
        $result = call_user_func($this->processor, $record);

        $this->assertArrayHasKey('excecution-time', $result['extra']);
        $this->assertSame(1.5, $result['extra']['excecution-time']);
    }
}
