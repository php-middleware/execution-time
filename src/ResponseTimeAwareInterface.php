<?php

namespace PhpMiddleware\ResponseTime;

interface ResponseTimeAwareInterface
{
    /**
     * @return float Response time in miliseconds
     */
    public function getResponseTime();
}
