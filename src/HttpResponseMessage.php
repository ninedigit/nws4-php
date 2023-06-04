<?php

namespace NineDigit\NWS4;

final class HttpResponseMessage
{
    public int $statusCode;
    public array $headers;
    public ?string $body;

    public function __construct(int $statusCode = 204, array $headers = [], ?string $body = null)
    {
    }

    public function isSuccessStatusCode(): bool
    {
        $success = $this->statusCode >= 200 && $this->statusCode <= 299;
        return $success;
    }

    public function ensureSuccessStatusCode()
    {
        $success = $this->isSuccessStatusCode();
        if (!$success) {
            $statusCode = $this->statusCode;
            throw new \Exception("Response '{$statusCode}' does not indicate success.");
        }
    }
}

