<?php

namespace NineDigit\NWS4;

final class HttpRequestMessageBuilder
{
    private HttpRequestHeadersBuilder $headersBuilder;
    private string $method;
    private string $url;
    private string $body;

    public function __construct(string $method, string $url, array $defaultHeaders = [])
    {
        $this->headersBuilder = new HttpRequestHeadersBuilder($defaultHeaders);
        $this->method = $method;
        $this->url = $url;
        $this->body = '';
    }

    function withBody(string|null $body): HttpRequestMessageBuilder
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Metóda na nastavenie hlavičiek
     * @param callable|array $headersOrCallable Asociatívne pole hlavičiek alebo vyvolateľná funkcia preberajúca HttpRequestHeadersBuilder.
     */
    function withHeaders($headersOrCallable): HttpRequestMessageBuilder
    {
        if (is_callable($headersOrCallable)) {
            $headersOrCallable($this->headersBuilder);
        } else if (is_array($headersOrCallable)) {
            $this->headersBuilder->set($headersOrCallable);
        } else {
            throw new \InvalidArgumentException("Expecting array or callable as an argument.");
        }

        return $this;
    }

    function build(): HttpRequestMessage
    {
        $headers = $this->headersBuilder->build();
        return new HttpRequestMessage($this->method, $this->url, $headers, $this->body);
    }

    public static function createGet(string $url, array $headers = []): HttpRequestMessageBuilder
    {
        return new HttpRequestMessageBuilder(HttpMethod::GET, $url, $headers);
    }

    public static function createPost(string $url, array $headers = []): HttpRequestMessageBuilder
    {
        return new HttpRequestMessageBuilder(HttpMethod::POST, $url, $headers);
    }

    public static function createPut(string $url, array $headers = []): HttpRequestMessageBuilder
    {
        return new HttpRequestMessageBuilder(HttpMethod::PUT, $url, $headers);
    }

    public static function createDelete(string $url, array $headers = []): HttpRequestMessageBuilder
    {
        return new HttpRequestMessageBuilder(HttpMethod::DELETE, $url, $headers);
    }

    public static function fromHttpRequest(HttpRequestMessage $httpRequest, array $defaultHeaders = []): HttpRequestMessageBuilder
    {
        $builder = new HttpRequestMessageBuilder($httpRequest->method, $httpRequest->url, $defaultHeaders);
        $builder->headersBuilder->set($httpRequest->headers);
        $builder->withBody($httpRequest->body);

        return $builder;
    }
}
