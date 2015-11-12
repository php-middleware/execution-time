<?php

namespace PhpMiddleware\ExecutionTime\TimerService;

interface TimerServiceInterface
{
    /**
     * @param callable $callback
     *
     * @return float time in miliseconds
     */
    public function measureCallbackExecutedTime(callable $callback);
}
