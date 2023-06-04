<?php

namespace NineDigit\NWS4;

final class HttpRequestHeadersBuilder
{
    private array $headers;

    public function __construct(array $defaultHeaders = [])
    {
        $this->headers = $defaultHeaders;
    }

    public function set(array $headers): HttpRequestHeadersBuilder
    {
        foreach ($headers as $key => $value) {
            $this->headers[$key] = $value;
        }
        return $this;
    }

    public function accept(string $value): HttpRequestHeadersBuilder
    {
        $this->headers['Accept'] = $value;
        return $this;
    }

    public function clear(): void
    {
        array_splice($this->headers, 0);
    }

    public function build(): array
    {
        return $this->headers;
    }
}
