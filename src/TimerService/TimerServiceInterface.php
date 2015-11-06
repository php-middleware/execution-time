<?php

namespace PhpMiddleware\ResponseTime\TimerService;

interface TimerServiceInterface
{
    /**
     * @param callable $callback
     *
     * @return float time in miliseconds
     */
    public function measureCallbackExecutedTime(callable $callback);
}
