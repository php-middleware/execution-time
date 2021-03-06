<?php

namespace PhpMiddleware\ExecutionTime\TimerService;

use PhpMiddleware\ExecutionTime\Exception\InvalidExecutionTimeException;
use Psr\Http\Message\ServerRequestInterface;

final class RequestTimeFloatService implements TimerServiceInterface
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
            $message = sprintf('$_SERVER does not contain %s key', self::REQUEST_TIME_FLOAT_KEY);
            throw new InvalidExecutionTimeException($message);
        }

        $callback();

        return $this->diffToNowInMiliseconds($serverParams[self::REQUEST_TIME_FLOAT_KEY]);
    }
}
