<?php

namespace NineDigit\NWS4\Tests;

use NineDigit\NWS4\ApiRequest;
use NineDigit\NWS4\HttpClient;
use NineDigit\NWS4\HttpClientOptions;
use PHPUnit\Framework\TestCase;


final class HttpClientTest extends TestCase {
    public function testCreateInstanceFromOptions() {
        $publicKey = "1234567890";
        $privateKey = "98765432109876543210";
        $url = "https://example.com";
        $httpClientOptions = new HttpClientOptions($publicKey, $privateKey, $url);
        $throws = false;

        try {
            $apiClient = new HttpClient($httpClientOptions);
        } catch (Exception | Error $e) {
            $throws = true;
        }

        $this->assertFalse($throws);
    }

    // public function testGetCustomersCreatesValidApiRequest() {
    //     $httpClient = new HttpClientMock(null, function ($r, $t, $s) {
    //         $this->assertInstanceOf(ApiRequest::class, $r);
    //         $this->assertEquals(HttpMethod::GET, $r->method);
    //         $this->assertEquals("/v1/customers?ids=%231&ids=%232&ids=%233&externalId=%234&modifiedAfter=2021-07-09T12%3A42%3A48.540872Z&isActive=true&cardId=%235&cardSerialNumbers=%236&cardSerialNumbers=%237&cardSerialNumbers=%238", $r->url);
    //         $this->assertIsArray($r->headers);
    //         $this->assertCount(0, $r->headers);
    //         $this->assertNull($r->payload);
    //         $this->assertEquals(GetCustomerListResultDto::class, $t);
    //         $this->assertTrue($s);
            
    //         return new GetCustomerListResultDto();
    //     });

    //     $apiClient = new HttpClient($httpClient);

    //     $customerFilter = (new CustomerFilterDto())
    //         ->setIds(array("#1", "#2", "#3"))
    //         ->setExternalId("#4")
    //         ->setModifiedAfter(DateTimeHelper::createUtc(2021, 7, 9, 12, 42, 48, 540872))
    //         ->setIsActive(true)
    //         ->setCardId("#5")
    //         ->setCardSerialNumbers(array("#6", "#7", "#8"));

    //     $apiClient->getCustomers($customerFilter);
    // }
}

