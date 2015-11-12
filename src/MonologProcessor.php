<?php

namespace PhpMiddleware\ExecutionTime;

final class MonologProcessor
{
    protected $provider;

    public function __construct(ExecutionTimeProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function __invoke(array $record)
    {
        $record['extra']['execution-time'] = $this->provider->getExecutionTime();

        return $record;
    }
}
