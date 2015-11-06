<?php

namespace PhpMiddlewareTest\ResponseTime\TimerService;

use PhpMiddleware\ResponseTime\TimerService\RequestTimeFloatService;
use Psr\Http\Message\ServerRequestInterface;

class RequestTimeFloatServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $service;
    protected $request;

    protected function setUp()
    {
        $this->request = $this->getMock(ServerRequestInterface::class);
        $this->service = new RequestTimeFloatService($this->request);
    }

    public function testMasureExecutedTime()
    {
        $this->request->expects($this->once())->method('getServerParams')->willReturn([RequestTimeFloatService::REQUEST_TIME_FLOAT_KEY => 1.0]);
        $result = $this->service->measureCallbackExecutedTime(function(){});

        $this->assertInternalType('float', $result);
    }

    /**
     * @expectedException \PhpMiddleware\ResponseTime\Exception\InvalidResponseTimeException
     */
    public function testMissingRequestTimeKey()
    {
        $this->request->expects($this->once())->method('getServerParams')->willReturn([]);
        $this->service->measureCallbackExecutedTime(function(){});
    }
}
