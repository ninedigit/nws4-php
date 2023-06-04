<?php

// Inspired by: https://github.com/aws/aws-sdk-php/blob/master/src/Signature/SignatureV4.php

namespace NineDigit\NWS4;

final class HttpRequestAuthorizationHeaderSigner implements HttpRequestSignerInterface
{
    private const emptyBodyHash = "e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855";

    private string $publicKey;
    private string $privateKey;
    private DateTimeServiceInterface $dateTimeService;

    public function __construct(string $publicKey, string $privateKey, ?DateTimeServiceInterface $dateTimeService = null)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        $this->dateTimeService = $dateTimeService ?? new LocalDateTimeService();
    }

    public function sign(HttpRequestMessage $request)
    {
        $utcNow = $this->dateTimeService->getNowUtc();
        $utcDateTimeNowFormat = $this->formatDateTime($utcNow);
        $utcDateNowFormat = $this->formatDate($utcNow);
        $body = $request->body;
        $bodyHash = empty($body) ? self::emptyBodyHash : hash('sha256', $body);

        // update the headers with required 'x-nd-content-sha256', 'x-nd-date' and 'host' values
        $request->headers['x-nd-date'] = $utcDateTimeNowFormat;
        $request->headers['x-nd-content-sha256'] = $bodyHash;
        $request->headers['host'] = self::createHostHeader($request->url);

        $canonicalizedResourcePath = self::canonicalizeResourcePath($request->url);

        // canonicalize the headers; we need the set of header names as well as the
        // names and values to go into the signature process
        $canonicalizedHeaderNames = self::canonicalizeHeaderNames($request->headers);
        $canonicalizedHeaders = $this->canonicalizeHeaders($request->headers);

        // if any query string parameters have been supplied, canonicalize them
        $canonicalizedQueryParameters = self::canonicalizeUrlQuery($request->url);

        // canonicalize the various components of the request
        $canonicalRequest = self::canonicalizeRequest(
            $request->method, $canonicalizedResourcePath, $canonicalizedQueryParameters,
            $canonicalizedHeaderNames, $canonicalizedHeaders, $bodyHash);

        // Compute signature
        $stringToSign = self::canonicalizeStringToSign(
            $utcDateTimeNowFormat, $this->publicKey, $canonicalRequest);

        $signature = self::computeSignature(
            $utcDateNowFormat, $this->privateKey, $stringToSign);

        // Build authorization header
        $authorizationHeader = self::createAuthorizationHeader(
            $this->publicKey, $canonicalizedHeaderNames, $utcDateTimeNowFormat, $signature);

        $request->headers['Authorization'] = $authorizationHeader;
    }

    /**
     * Get time stamp in "yyyy-MM-ddTHH:mm:ss.fffK" format
     */
    private static function formatDateTime(\DateTime $date)
    {
        return $date->format('Y-m-d\TH:i:s.v') . 'Z';
    }

    private static function formatDate(\DateTime $date): string
    {
        return $date->format('Y-m-d');
    }

    private static function createHostHeader(string $url): string
    {
        $urlParts = parse_url($url);

        if (!isset($urlParts['host'])) {
            throw new \Exception("Host part of URL was not found. Please provide absolute URL.");
        }

        $host = $urlParts['host'];
        $port = $urlParts['port'] ?? '';

        if (!empty($port) && $port !== "80" && $port !== "443") {
            $host .= ':' . $urlParts['port'];
        }

        return $host;
    }

    /**
     * Returns the canonical collection of header names that will be included in
     * the signature. For NWS4, all header names must be included in the process
     * in sorted canonicalized order.
     */
    private static function canonicalizeHeaderNames(array $headers): string
    {
        $sortedHeaderKeys = array_keys($headers);
        usort($sortedHeaderKeys, function (string $a, string $b) {
            return self::stringCompare($a, $b, false);
        });

        $headersToSignLowercase = array_map('strtolower', $sortedHeaderKeys);

        return implode(';', $headersToSignLowercase);
    }

    /**
     * Computes the canonical headers with values for the request.
     * For NWS4, all headers must be included in the signing process.
     */
    private function canonicalizeHeaders(array $headers): string
    {
        if (empty($headers)) {
            return '';
        }

        $sortedHeaderMap = [];

        // step1: sort the headers into lower-case format; we create a new
        // map to ensure we can do a subsequent key lookup using a lower-case
        // key regardless of how 'headers' was created.
        foreach ($headers as $key => $value) {
            $sortedHeaderMap[strtolower($key)] = $value;
        }

        uksort($sortedHeaderMap, function (string $a, string $b) {
            return self::stringCompare($a, $b, true);
        });

        // step2: form the canonical header:value entries in sorted order.
        // Multiple white spaces in the values should be compressed to a single
        // space.
        $serializedHeaders = [];
        foreach ($sortedHeaderMap as $key => $value) {
            $serializedHeaders[] = $key . ":" . trim(preg_replace('/\s+/', ' ', $value));
        }

        return implode("\n", $serializedHeaders);
    }

    private static function canonicalizeUrlQuery(string $url): string
    {
        $queryParams = self::getQueryParameters($url);

        return self::canonicalizeQueryParameters($queryParams);
    }

    private static function getQueryParameters(string $url): array
    {
        $urlParts = parse_url($url);
        $queryParams = [];

        if (array_key_exists('query', $urlParts) && !empty($urlParts['query'])) {
            $query = $urlParts['query'];
            $queryParts = explode('&', $query);

            foreach ($queryParts as $queryPart) {
                $queryParamParts = explode('=', $queryPart);
                $queryParams[$queryParamParts[0]] = count($queryParamParts) > 1 ? $queryParamParts[1] : '';
            }
        }

        return $queryParams;
    }

    private static function canonicalizeQueryParameters(array $queryParams): string
    {
        if (!$queryParams) {
            return '';
        }

        uksort($queryParams, function (string $a, string $b) {
            return self::stringCompare($a, $b, true);
        });

        $keyValuePairs = [];

        foreach ($queryParams as $key => $value) {
            $keyValuePairs[] = rawurlencode($key) . '=' . rawurlencode($value);
        }

        return implode('&', $keyValuePairs);
    }

    private static function stringCompare(string $first, string $second, $isCaseSensitive = true)
    {
        if (!$isCaseSensitive) {
            $first = strtoupper($first);
            $second = strtoupper($second);
        }

        return strcmp($first, $second);
    }

    private static function canonicalizeResourcePath(string $url): string
    {
        $urlPath = parse_url($url, PHP_URL_PATH);
        if (empty($urlPath)) {
            return '/';
        }

        $encodedUrlPath = rawurlencode(ltrim($urlPath, '/'));

        return '/' . str_replace('%2F', '/', $encodedUrlPath);
    }

    private static function canonicalizeRequest(
        string $httpMethod,
        string $canonicalizedResourcePath,
        string $queryParameters,
        string $canonicalizedHeaderNames,
        string $canonicalizedHeaders,
        string $bodyHash
    ): string
    {
        $array = [
            $httpMethod,
            $canonicalizedResourcePath,
            $queryParameters,
            $canonicalizedHeaders,
            $canonicalizedHeaderNames,
            $bodyHash
        ];

        return implode("\n", $array);
    }

    private static function canonicalizeStringToSign(string $dateTimeStamp, string $publicKey, string $canonicalRequest): string
    {
        $array = [
            'NWS4-HMAC-SHA256',
            $dateTimeStamp,
            $publicKey,
            hash('sha256', $canonicalRequest),
        ];

        return implode("\n", $array);
    }

    private static function computeSignature(string $utcDateFormat, string $privateKey, $data): string
    {
        $key = 'NWS4' . $privateKey;
        $signingKey = hash_hmac('SHA256', $utcDateFormat, $key, true);

        return hash_hmac('SHA256', $data, $signingKey);
    }

    private static function createAuthorizationHeader(string $publicKey, string $signedHeaders, string $timestamp, string $signature): string
    {
        $timestampEnc = rawurlencode($timestamp);
        $signedHeadersEnc = rawurlencode($signedHeaders);
        $authorization = [
            'Credential=' . $publicKey,
            'SignedHeaders=' . $signedHeadersEnc,
            'Timestamp=' . $timestampEnc,
            'Signature=' . $signature
        ];
        $authorizationEnc = rawurlencode(implode(',', $authorization));

        return 'NWS4-HMAC-SHA256 ' . $authorizationEnc;
    }
}
