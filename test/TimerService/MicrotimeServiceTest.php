<?php

namespace PhpMiddlewareTest\ResponseTime\TimerService;

use PhpMiddleware\ResponseTime\TimerService\MicrotimeService;

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
