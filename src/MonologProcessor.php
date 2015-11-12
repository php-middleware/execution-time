<?php

namespace PhpMiddleware\ResponseTime;

final class MonologProcessor
{
    protected $provider;

    public function __construct(ResponseTimeProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function __invoke(array $record)
    {
        $record['extra']['excecution-time'] = $this->provider->getExcecutionTime();

        return $record;
    }
}
