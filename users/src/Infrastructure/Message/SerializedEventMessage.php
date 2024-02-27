<?php

namespace App\Infrastructure\Message;

class SerializedEventMessage
{
    private string $serializedData;

    public function __construct(string $serializedData)
    {
        $this->serializedData = $serializedData;
    }

    public function getSerializedData(): string
    {
        return $this->serializedData;
    }
}

