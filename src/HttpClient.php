<?php

namespace NineDigit\NWS4;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use NineDigit\NWS4\SerializerInterface;


final class HttpClient implements HttpClientInterface
{
    private HttpRequestSignerInterface $httpRequestSigner;
    private SerializerInterface $serializer;
    private array $defaultHttpHeaders;
    private $client;
    private $url;

    public function __construct(HttpClientOptions $options)
    {
        $this->url = $options->url;

        $guzzleHttpClientConfig = [
            //'base_uri' => $this->url
        ];

        if (!empty($options->proxyUrl)) {
            $guzzleHttpClientConfig["proxy"] = $options->proxyUrl;
        }

        $this->client = new GuzzleHttpClient($guzzleHttpClientConfig);

        $this->httpRequestSigner = $options->httpRequestSigner
            ?? new HttpRequestAuthorizationHeaderSigner($options->publicKey, $options->privateKey);

        $this->defaultHttpHeaders = $options->defaultHeaders;
    }

    public function send(HttpRequestMessage $request, $sign = false): HttpResponseMessage
    {
        $requestMessage = $this->createRequestMessage($request, $sign);
        $responseMessage = $this->sendRequestMessage($requestMessage);

        return $responseMessage;
    }

    private function createRequestMessage(HttpRequestMessage $request, $sign = false): HttpRequestMessage
    {
        $url = $request->url;

        if (!str_starts_with($url, $this->url)) {
            $url = $this->url . '/' . trim($url, '/');
        }

        $body = $request->body;
        $headers = [...$this->defaultHttpHeaders, ...$request->headers];
        $httpRequestMessage = new HttpRequestMessage($request->method, $url, $headers, $body);

        if ($sign === true) {
            $this->signRequestMessage($httpRequestMessage);
        }

        return $httpRequestMessage;
    }

    private function sendRequestMessage(HttpRequestMessage $request): HttpResponseMessage
    {
        $body = '';

        try {
            $guzzleRequest = new Request(
                $request->method,
                $request->url,
                $request->headers,
                $request->body);

            $guzzleResponse = $this->client->send($guzzleRequest, ['debug' => true]);
            $body = $guzzleResponse->getBody();
        } catch (RequestException $ex) {
            if ($ex->hasResponse()) {
                $guzzleResponse = $ex->getResponse();
                $body = $ex->getResponse()->getBody()->getContents();
            } else {
                throw $ex;
            }
        }

        foreach ($guzzleResponse->getHeaders() as $key => $value)
            $headers[$key] = $value;

        $response = new HttpResponseMessage();
        $response->statusCode = $guzzleResponse->getStatusCode();
        $response->headers = $headers;
        $response->body = $body;

        return $response;
    }

    private function signRequestMessage(HttpRequestMessage $request): void
    {
        $this->httpRequestSigner->sign($request);
    }
}
