<?php

namespace PhpMiddleware\ResponseTime;

use PhpMiddleware\ResponseTime\TimerService\TimerServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ResponseTimeMiddleware implements ResponseTimeAwareInterface
{
    const HEADER_RESPONSE_TIME = 'X-Response-Time';

    protected $timer;
    protected $responseHeader;
    protected $responseTime;

    public function __construct(TimerServiceInterface $timer, $responseHeader = self::HEADER_RESPONSE_TIME)
    {
        $this->timer = $timer;
        $this->responseHeader = $responseHeader;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $callbackToMeasure = function() use ($request, &$response, $next) {
            $response = $next ? $next($request, $response) : $response;
        };

        $this->responseTime = $this->timer->measureCallbackExecutedTime($callbackToMeasure);

        if (!is_float($this->responseTime)) {
            throw new \Exception();
        }
        if (!empty($this->responseHeader) && is_string($this->responseHeader)) {
            $response = $response->withHeader($this->responseHeader, $this->responseTime);
        }
        return $response;
    }

    public function getResponseTime()
    {
        if ($this->responseTime === null) {
            throw new Exception();
        }
        return $this->responseTime;
    }
}
