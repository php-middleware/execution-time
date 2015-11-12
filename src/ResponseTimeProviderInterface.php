<?php

namespace PhpMiddleware\ResponseTime;

interface ResponseTimeProviderInterface
{
    /**
     * @return float Response time in miliseconds
     */
    public function getExcecutionTime();
}
