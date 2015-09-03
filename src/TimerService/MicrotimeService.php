<?php

namespace PhpMiddleware\ResponseTime\TimerService;

class MicrotimeService implements TimerServiceInterface
{
    use MicrotimeTrait;

    public function measureCallbackExecutedTime(callable $callback)
    {
        $start = $this->getMicrotime();

        $callback();

        return $this->diffToNowInMiliseconds($start);
    }

}
