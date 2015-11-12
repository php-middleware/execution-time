<?php

namespace PhpMiddlewareTest\ExecutionTime;

use PhpMiddleware\ExecutionTime\ExecutionTimeMiddleware;
use PhpMiddleware\ExecutionTime\TimerService\TimerServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ExecutionTimeMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    protected $middleware;
    protected $timer;

    protected function setUp()
    {
        $this->timer = $this->getMock(TimerServiceInterface::class);

        $this->middleware = new ExecutionTimeMiddleware($this->timer);
    }

    public function testExecutionTime()
    {
        $this->timer->expects($this->once())->method('measureCallbackExecutedTime')->willReturnCallback(function (callable $callback) {
            $callback();
            return 0.5;
        });

        $request = $this->getMock(ServerRequestInterface::class);
        $response = $this->getMock(ResponseInterface::class);


        $closure = function () use ($response) {
            return clone $response;
        };
        $response->expects($this->once())->method('withHeader')->with(ExecutionTimeMiddleware::HEADER_RESPONSE_TIME, '0.5')->willReturnCallback($closure);

        $result = call_user_func($this->middleware, $request, $response, function($request, $response) {
            return $response;
        });

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertNotSame($response, $result);
        $this->assertSame(0.5, $this->middleware->getExecutionTime());
    }

    /**
     * @expectedException \PhpMiddleware\ExecutionTime\Exception\InvalidExecutionTimeException
     */
    public function testServiceReturnWrongTime()
    {
        $this->timer->expects($this->once())->method('measureCallbackExecutedTime')->willReturn(1);

        $request = $this->getMock(ServerRequestInterface::class);
        $response = $this->getMock(ResponseInterface::class);

        call_user_func($this->middleware, $request, $response, function($request, $response) {
            return $response;
        });
    }

    /**
     * @expectedException \PhpMiddleware\ExecutionTime\Exception\NotMeasuredExecutionTimeException
     */
    public function testGetReponseTimeBeforeRun()
    {
        $this->middleware->getExecutionTime();
    }

    public function testNoReturnHeaderIfEmptySetup()
    {
        $middleware = new ExecutionTimeMiddleware($this->timer, null);

        $this->timer->expects($this->once())->method('measureCallbackExecutedTime')->willReturnCallback(function () {
            return 0.5;
        });

        $request = $this->getMock(ServerRequestInterface::class);
        $response = $this->getMock(ResponseInterface::class);

        $response->expects($this->never())->method('withHeader');

        $result = call_user_func($middleware, $request, $response, function($request, $response) {
            return $response;
        });

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertSame(0.5, $middleware->getExecutionTime());
    }
}
