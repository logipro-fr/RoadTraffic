<?php

namespace RoadTraffic\Application\Service\RoadTraffic\Responses;

class ResponseUrlTomtom implements ResponseUrlInterface
{
    public function __construct(
        public readonly string $url,
    ) {
    }

    public function __toString(): string
    {
        return strval($this->url);
    }
}
