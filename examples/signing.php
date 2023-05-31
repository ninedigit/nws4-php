<?php

namespace NineDigit\NWS4;

require '../vendor/autoload.php';

$apiUrl = "http://example.com:8080/api";
$publicKey = "d948ec22e47790caacce234b792a0f117d85c365";
$privateKey = "5070adfb2bedf1c0c97d5c8cfa8c794d513249b802ea10596a175d88828abc19";
$signer = new HttpRequestAuthorizationHeaderSigner($publicKey, $privateKey);

// GET ALL

$request = new HttpRequestMessage("GET", "{$apiUrl}/v1/articlecategories?\$take=10");
$signer->sign($request);

echo '<pre>';
print_r($request);
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

$signer->sign($request);

echo '<pre>';
print_r($request);
echo '</pre>';

// GET
$request = new HttpRequestMessage("GET", "{$apiUrl}/v1/articlecategories/XYZ");
$signer->sign($request);

echo '<pre>';
print_r($request);
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

$signer->sign($request);

echo '<pre>';
print_r($request);
echo '</pre>';

// DELETE

$request = new HttpRequestMessage("DELETE", "{$apiUrl}/v1/articlecategories/XYZ");
$signer->sign($request);

echo '<pre>';
print_r($request);
echo '</pre>';

?>