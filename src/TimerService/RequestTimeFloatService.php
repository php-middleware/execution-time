<?php

namespace PhpMiddleware\ResponseTime\TimerService;

use Psr\Http\Message\ServerRequestInterface;

class RequestTimeFloatService implements TimerServiceInterface
{
    use MicrotimeTrait;

    const REQUEST_TIME_FLOAT_KEY = 'REQUEST_TIME_FLOAT';

    protected $request;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function measureCallbackExecutedTime(callable $callback)
    {
        $serverParams = $this->request->getServerParams();

        if (empty($serverParams[self::REQUEST_TIME_FLOAT_KEY])) {
            throw new \Exception();
        }

        $callback();

        return $this->diffToNowInMiliseconds($serverParams[self::REQUEST_TIME_FLOAT_KEY]);
    }
}
