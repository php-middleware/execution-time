<?php

namespace PhpMiddleware\ResponseTime;

use PhpMiddleware\ResponseTime\TimerService\TimerServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ResponseTimeMiddleware implements ResponseTimeProviderInterface
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

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $callbackToMeasure = function () use ($request, &$response, $next) {
            $response = $next($request, $response);
        };

        $this->responseTime = $this->timer->measureCallbackExecutedTime($callbackToMeasure);

        if (!is_float($this->responseTime)) {
            throw new Exception\InvalidResponseTimeException('Response time must be a float');
        }
        if (!empty($this->responseHeader) && is_string($this->responseHeader)) {
            $response = $response->withHeader($this->responseHeader, (string) $this->responseTime);
        }
        return $response;
    }

    public function getExcecutionTime()
    {
        if ($this->responseTime === null) {
            throw new Exception\NotMeasuredResponseTimeException('Response time is not measured yet');
        }
        return $this->responseTime;
    }
}
