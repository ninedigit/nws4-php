<?php

namespace NineDigit\NWS4\Tests;

use NineDigit\NWS4\DateTimeServiceInterface;
use NineDigit\NWS4\HttpRequestAuthorizationHeaderSigner;
use NineDigit\NWS4\HttpRequestMessage;
use PHPUnit\Framework\TestCase;

class StaticDateTimeService implements DateTimeServiceInterface {
    private $dateTime;

    public function __construct(\DateTime $dateTime) {
        $this->dateTime = $dateTime;
    }

    public function getNowUtc(): \DateTime {
        return $this->dateTime;
    }
}

final class HttpRequestAuthorizationHeaderSignerTest extends TestCase {
    const API_URL = "http://example.com:8080/api";
    const PUBLIC_KEY = "d948ec22e47790caacce234b792a0f117d85c365";
    const PRIVATE_KEY = "5070adfb2bedf1c0c97d5c8cfa8c794d513249b802ea10596a175d88828abc19";

    public function testSignature() {
        $now = $this->createUtcDateTime(2023, 05, 30, 13, 17, 01, 992000);
        $dateTimeService = new StaticDateTimeService($now);
        $signer = new HttpRequestAuthorizationHeaderSigner(self::PUBLIC_KEY, self::PRIVATE_KEY, $dateTimeService);
        $headers = array(
            "Content-Type" => "application/json",
            "Accept" => "application/json"
        );
        $method = "POST";
        $body = "{\"name\":\"John\"}";
        $url = self::API_URL . "/user?i=1\$ref=1&validate";
        
        $requestMessage = new HttpRequestMessage($method, $url, $headers, $body);
        $signer->sign($requestMessage);

        $this->assertSame($method, $requestMessage->method);
        $this->assertSame($url, $requestMessage->url);
        $this->assertSame($body, $requestMessage->body);
        $this->assertCount(6, $requestMessage->headers);

        $this->arrayHasKey("x-nd-date", $requestMessage->headers);
        $this->assertSame("2023-05-30T13:17:01.992Z", $requestMessage->headers["x-nd-date"]);

        $this->arrayHasKey("x-nd-content-sha256", $requestMessage->headers);
        $this->assertSame("f498513cc6d902a32254a1d795e2031636dea18dce65ba10e94e83189e50cbb8", $requestMessage->headers["x-nd-content-sha256"]);

        $this->arrayHasKey("host", $requestMessage->headers);
        $this->assertSame("example.com:8080", $requestMessage->headers["host"]);

        $this->arrayHasKey("Authorization", $requestMessage->headers);
        $this->assertSame("NWS4-HMAC-SHA256 Credential%3Dd948ec22e47790caacce234b792a0f117d85c365%2CSignedHeaders%3Daccept%253Bcontent-type%253Bhost%253Bx-nd-content-sha256%253Bx-nd-date%2CTimestamp%3D2023-05-30T13%253A17%253A01.992Z%2CSignature%3D87c7a2057285f0db8dc3b7c95c15fe5476f5ab44b73b3e15990d8b1a837d4ddb", $requestMessage->headers["Authorization"]);
    }

    private function createUtcDateTime(int $year, int $month, int $day, int $hour, int $minute, int $second = 0, int $microsecond = 0): \DateTime {
        $date = new \DateTime("now", new \DateTimeZone("UTC"));
        $date = $date->setDate($year, $month, $day);
        $date = $date->setTime($hour, $minute, $second, $microsecond);

        return $date;
    }
}

?>
