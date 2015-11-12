<?php

namespace PhpMiddleware\ExecutionTime\TimerService;

trait MicrotimeTrait
{
    protected function getMicrotime()
    {
        return microtime(true);
    }

    protected function convertMicrotimeToMilliseconds($time)
    {
        return $time * 1000;
    }

    protected function diffToNowInMiliseconds($microtime)
    {
        return $this->convertMicrotimeToMilliseconds($this->getMicrotime() - $microtime);
    }
}
