<?php

namespace PhpMiddlewareTest\ExecutionTime\TimerService;

use PhpMiddleware\ExecutionTime\TimerService\MicrotimeService;

class MicrotimeServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testMeasure()
    {
        $service = new MicrotimeService();

        $result = $service->measureCallbackExecutedTime(function () {
            usleep(10000);
        });

        $this->assertGreaterThan(10, $result);
    }
}
