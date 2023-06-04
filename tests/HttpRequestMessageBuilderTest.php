<?php

namespace NineDigit\NWS4\Tests;

use NineDigit\NWS4\HttpRequestMessage;
use NineDigit\NWS4\HttpRequestMessageBuilder;
use PHPUnit\Framework\TestCase;

final class HttpRequestMessageBuilderTest extends TestCase {
    public function testFromHttpRequestCreatesCorrectRequest() {
        $url = "example.com/resource";
        $headers = array(
            "Content-Type" => "application/json",
            "Accept" => "application/json"
        );
        $defaultHeaders = array(
            "Accept" => "application/xml",
            "User-Agent" => "CUSTOM"
        );

        $requestMessage = new HttpRequestMessage("POST", $url, $headers, '{}');
        $request = HttpRequestMessageBuilder::fromHttpRequest($requestMessage, $defaultHeaders)->build();

        $this->assertEquals("POST", $request->method);
        $this->assertEquals("example.com/resource", $request->url);
        $this->assertEquals("{}", $request->body);
        $this->assertEquals(array(
            "Accept" => "application/json",
            "User-Agent" => "CUSTOM",
            "Content-Type" => "application/json"
        ), $request->headers);
    }

    public function testBuildOfCreateGetCreatesCorrectRequest() {
        $url = "example.com/resource";
        $headers = array(
            "Accept" => "application/json"
        );

        $request = HttpRequestMessageBuilder::createGet($url, $headers)->build();

        $this->assertEquals("GET", $request->method);
        $this->assertEquals($url, $request->url);
        $this->assertEquals($headers, $request->headers);
    }

    public function testBuildOfCreatePostCreatesCorrectRequest() {
        $url = "example.com/resource";
        $headers = array(
            "Accept" => "application/json"
        );

        $request = HttpRequestMessageBuilder::createPost($url, $headers)->build();

        $this->assertEquals("POST", $request->method);
        $this->assertEquals($url, $request->url);
        $this->assertEquals($headers, $request->headers);
    }

    public function testBuildOfCreatePutCreatesCorrectRequest() {
        $url = "example.com/resource";
        $headers = array(
            "Accept" => "application/json"
        );

        $request = HttpRequestMessageBuilder::createPut($url, $headers)->build();

        $this->assertEquals("PUT", $request->method);
        $this->assertEquals($url, $request->url);
        $this->assertEquals($headers, $request->headers);
    }

    public function testBuildOfCreateDeleteCreatesCorrectRequest() {
        $url = "example.com/resource";
        $headers = array(
            "Accept" => "application/json"
        );

        $request = HttpRequestMessageBuilder::createDelete($url, $headers)->build();

        $this->assertEquals("DELETE", $request->method);
        $this->assertEquals($url, $request->url);
        $this->assertEquals($headers, $request->headers);
    }

    public function testBuildOfCreatePostWithDefaultHeadersAndBodyCreatesCorrectRequest() {
        $url = "example.com/resource";
        $body = "{}";

        $headers = array(
            "Content-Type" => "application/json",
            "Accept" => "application/xml"
        );

        $request = HttpRequestMessageBuilder::createPost($url, $headers)
            ->withHeaders(array(
                "Accept" => "application/json"
            ))
            ->withBody($body)
            ->build();

        $this->assertEquals("POST", $request->method);
        $this->assertEquals($url, $request->url);
        $this->assertEquals(array(
            "Content-Type" => "application/json",
            "Accept" => "application/json"
        ), $request->headers);
    }
}

?>
