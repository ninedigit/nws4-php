<?php

namespace NineDigit\NWS4;

use Exception;

final class HttpResponseMessage
{
    public int $statusCode;
    public array $headers;
    public string|null $body;

    public function __construct(int $statusCode = 204, array $headers = [], ?string $body = null)
    {
    }

    public function isSuccessStatusCode(): bool
    {
        return $this->statusCode >= 200 && $this->statusCode <= 299;
    }

    public function ensureSuccessStatusCode(): void
    {
        $success = $this->isSuccessStatusCode();
        if (!$success) {
            $statusCode = $this->statusCode;
            throw new Exception("Response '{$statusCode}' does not indicate success.");
        }
    }
}

