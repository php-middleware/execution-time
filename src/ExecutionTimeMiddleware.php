<?php

namespace PhpMiddleware\ExecutionTime;

use PhpMiddleware\ExecutionTime\TimerService\TimerServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ExecutionTimeMiddleware implements ExecutionTimeProviderInterface
{
    const HEADER_RESPONSE_TIME = 'X-Excecution-Time';

    protected $timerService;
    protected $responseHeader;
    protected $executionTime;

    public function __construct(TimerServiceInterface $timer, $responseHeader = self::HEADER_RESPONSE_TIME)
    {
        $this->timerService = $timer;
        $this->responseHeader = $responseHeader;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $callbackToMeasure = function () use ($request, &$response, $next) {
            $response = $next($request, $response);
        };

        $this->executionTime = $this->timerService->measureCallbackExecutedTime($callbackToMeasure);

        if (!is_float($this->executionTime)) {
            throw new Exception\InvalidExecutionTimeException('Response time must be a float');
        }
        if (!empty($this->responseHeader) && is_string($this->responseHeader)) {
            $response = $response->withHeader($this->responseHeader, (string) $this->executionTime);
        }
        return $response;
    }

    public function getExecutionTime()
    {
        if ($this->executionTime === null) {
            throw new Exception\NotMeasuredExecutionTimeException('Response time is not measured yet');
        }
        return $this->executionTime;
    }
}
