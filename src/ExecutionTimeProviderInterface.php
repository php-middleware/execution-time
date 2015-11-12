<?php

namespace PhpMiddleware\ExecutionTime;

interface ExecutionTimeProviderInterface
{
    /**
     * @return float Response time in miliseconds
     */
    public function getExecutionTime();
}
