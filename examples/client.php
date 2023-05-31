<?php

namespace NineDigit\NWS4;

require '../vendor/autoload.php';

$apiUrl = "http://213.160.191.53:4000/api";
$publicKey = "d948ec22e47790caacce234b792a0f117d85c365";
$privateKey = "5070adfb2bedf1c0c97d5c8cfa8c794d513249b802ea10596a175d88828abc19";
$httpClientOptions = new HttpClientOptions($publicKey, $privateKey, $apiUrl);
$httpClient = new HttpClient($httpClientOptions);

// GET ALL

$request = new HttpRequestMessage("GET", "v1/articlecategories?\$take=1000");
$response = $httpClient->send($request, true);

echo '<pre>';
print_r($response);
echo '</pre>';

// POST
$request = new HttpRequestMessage("POST", "{$apiUrl}/v1/articlecategories", [
    "Content-Type" => "application/json",
    "Accept" => "application/json"
], "{
    \"Label\": \"XYZ\",
    \"Description\": \"Testovacia tovarová skupina\",
    \"CourseNumber\": 1,
    \"Color\": null
}");

$response = $httpClient->send($request, true);

echo '<pre>';
print_r($response);
echo '</pre>';

// GET
$request = new HttpRequestMessage("GET", "{$apiUrl}/v1/articlecategories/XYZ");
$response = $httpClient->send($request, true);

echo '<pre>';
print_r($response);
echo '</pre>';

// PUT
$request = new HttpRequestMessage("PUT", "{$apiUrl}/v1/articlecategories/XYZ", [
    "Content-Type" => "application/json",
    "Accept" => "application/json"
], "{
    \"Label\": \"XYZ\",
    \"Description\": \"Testovacia tovarová skupina\",
    \"CourseNumber\": 1,
    \"Color\": null
}");

$response = $httpClient->send($request, true);

echo '<pre>';
print_r($response);
echo '</pre>';

// DELETE

$request = new HttpRequestMessage("DELETE", "{$apiUrl}/v1/articlecategories/XYZ");
$response = $httpClient->send($request, true);

echo '<pre>';
print_r($response);
echo '</pre>';

?>