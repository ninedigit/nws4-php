<?php

namespace NineDigit\NWS4\Tests\Integration;

use NineDigit\NWS4\HttpClient;
use NineDigit\NWS4\HttpClientOptions;
use NineDigit\NWS4\HttpMethod;
use NineDigit\NWS4\HttpRequestMessage;
use NineDigit\NWS4\HttpResponseMessage;
use PHPUnit\Framework\TestCase;


final class HttpClientIntegrationTest extends TestCase {
    
    private function getDemoAccountSettings() {
        return HttpClientOptions::load(dirname(__FILE__) . '/settings.json');
    }

    public function testGetProfile() {
        $settings = $this->getDemoAccountSettings();
        $httpClient = new HttpClient($settings);

        $request = new HttpRequestMessage(HttpMethod::GET, "/auth");

        $result = $httpClient->send($request, true);

        $this->assertInstanceOf(HttpResponseMessage::class, $result);
        $this->assertEquals(200, $result->statusCode);
    }

    // public function testGetCustomer() {
    //     $settings = $this->getDemoAccountSettings();
    //     $apiClient = new HttpClient($settings->apiClientOptions);
    //     $customerId = "3a0537ea-Http2-2b4b-d521-02db003b276c";

    //     $customer = $apiClient->getCustomer($customerId);

    //     $this->assertInstanceOf(CustomerDto::class, $customer);
    // }

    // public function testFindCustomer() {
    //     $settings = $this->getDemoAccountSettings();
    //     $apiClient = new HttpClient($settings->apiClientOptions);
    //     $customerId = "3a0537ea-Http2-2b4b-d521-02db003b276c";

    //     $customer = $apiClient->getCustomer($customerId);

    //     $this->assertInstanceOf(CustomerDto::class, $customer);
    // }

    // public function testGetNonExistingCustomer() {
    //     $settings = $this->getDemoAccountSettings();
    //     $apiClient = new HttpClient($settings->HttpClientOptions);

    //     $customerId = "00000000-0000-0000-0000-000000000000";

    //     try
    //     {
    //         $customer = $apiClient->getCustomer($customerId);
    //         $this->fail("ApiException not thrown.");
    //     }
    //     catch (ApiException $ex)
    //     {
    //         $this->assertEquals(404, $ex->statusCode);
    //     }
    // }

    // public function testFindNonExistingCustomer() {
    //     $settings = $this->getDemoAccountSettings();
    //     $apiClient = new HttpClient($settings->apiClientOptions);
    //     $customerId = "00000000-Http0-0000-0000-000000000000";

    //     $customer = $apiClient->findCustomer($customerId);

    //     $this->assertNull($customer);
    // }

    // public function testRegisterReceiptUsingPosPrinter() {
    //     $settings = $this->getDemoAccountSettings();
    //     $apiClient = new HttpClient($settings->HttpClientOptions);

    //     $posPrinterOptions = new PosReceiptPrinterOptions();
    //     $receiptPrinter = new PosReceiptPrinterDto($posPrinterOptions);

    //     $cashRegisterCode = $settings->cashRegisterCode;
    //     $externalId = "e52ff4d1-f2ed-4493-9e9a-a73739b1ba23";

    //     $item = new ReceiptRegistrationItemDto(
    //         ReceiptItemType::POSITIVE,
    //         "Coca Cola 0.25l",
    //         1.29,
    //         20.00,
    //         new QuantityDto(2, "ks"),
    //         2.58
    //     );

    //     $payment = new ReceiptRegistrationPaymentDto(2.58, "Hotovosť");

    //     $receiptRegistrationRequest = new Receipts\CreateReceiptRegistrationRequestDto(
    //         ReceiptType::CASH_REGISTER,
    //         $cashRegisterCode,
    //         $externalId,
    //         [$item],
    //         [$payment]
    //     );

    //     $receiptRegistrationRequest->headerText = "www.ninedigit.sk";
    //     $receiptRegistrationRequest->footerText = "Ďakujeme za váš nákup.";

    //     $validityTimeSpan = 1;

    //     $createReceiptRegistration = new CreateReceiptRegistrationDto(
    //         $receiptPrinter, $receiptRegistrationRequest, $validityTimeSpan);

    //     $receiptRegistration = $apiClient->registerReceipt($createReceiptRegistration);

    //     $this->assertNotEquals(RegistrationState::FAILED, $receiptRegistration->state);
    // }

    // public function testRegisterReceiptUsingPosPrinterWithNonEmptyOptions() {
    //     $settings = $this->getDemoAccountSettings();
    //     $apiClient = new HttpClient($settings->HttpClientOptions);

    //     $posPrinterOptions = new PosReceiptPrinterOptions();
    //     $posPrinterOptions->openDrawer = true;
    //     $receiptPrinter = new PosReceiptPrinterDto($posPrinterOptions);

    //     $cashRegisterCode = $settings->cashRegisterCode;
    //     $externalId = getGUID();

    //     $item = new ReceiptRegistrationItemDto(
    //         ReceiptItemType::POSITIVE,
    //         "Coca Cola 0.25l",
    //         1.29,
    //         20.00,
    //         new QuantityDto(2, "ks"),
    //         2.58
    //     );

    //     $payment = new ReceiptRegistrationPaymentDto(2.58, "Hotovosť");

    //     $receiptRegistrationRequest = new Receipts\CreateReceiptRegistrationRequestDto(
    //         ReceiptType::CASH_REGISTER,
    //         $cashRegisterCode,
    //         $externalId,
    //         [$item],
    //         [$payment]
    //     );

    //     $receiptRegistrationRequest->headerText = "www.ninedigit.sk";
    //     $receiptRegistrationRequest->footerText = "Ďakujeme za váš nákup.";

    //     $validityTimeSpan = $settings->validityTimeSpan;

    //     $createReceiptRegistration = new CreateReceiptRegistrationDto(
    //         $receiptPrinter, $receiptRegistrationRequest, $validityTimeSpan);

    //     $receiptRegistration = $apiClient->registerReceipt($createReceiptRegistration);

    //     $this->assertEquals(RegistrationState::PROCESSED, $receiptRegistration->state);
    // }

    // public function testRegisterReceiptUsingPosPrinterWithNonEmptyOptionsAsExpired() {
    //     $settings = $this->getDemoAccountSettings();
    //     $apiClient = new HttpClient($settings->HttpClientOptions);

    //     $posPrinterOptions = new PosReceiptPrinterOptions();
    //     $posPrinterOptions->openDrawer = true;
    //     $receiptPrinter = new PosReceiptPrinterDto($posPrinterOptions);

    //     // $receiptPrinter = new PdfReceiptPrinterDto($pdfPrinterOptions);

    //     // $emailPrinterOptions = new EmailReceiptPrinterOptions("mail@example.com");
    //     // $emailPrinterOptions->subject = "Váš elektronický doklad";
    //     // $receiptPrinter = new EmailReceiptPrinterDto($emailPrinterOptions);

    //     $cashRegisterCode = $settings->cashRegisterCode;
    //     $externalId = "e52ff4d1-f2ed-4493-9e9a-a73739b1ba23";

    //     $item = new ReceiptRegistrationItemDto(
    //         ReceiptItemType::POSITIVE,
    //         "Coca Cola 0.25l",
    //         1.29,
    //         20.00,
    //         new QuantityDto(2, "ks"),
    //         2.58
    //     );

    //     $payment = new ReceiptRegistrationPaymentDto(2.58, "Hotovosť");

    //     $receiptRegistrationRequest = new Receipts\CreateReceiptRegistrationRequestDto(
    //         ReceiptType::CASH_REGISTER,
    //         $cashRegisterCode,
    //         $externalId,
    //         [$item],
    //         [$payment]
    //     );

    //     $receiptRegistrationRequest->headerText = "www.ninedigit.sk";
    //     $receiptRegistrationRequest->footerText = "Ďakujeme za váš nákup.";

    //     $validityTimeSpan = 0;

    //     $createReceiptRegistration = new CreateReceiptRegistrationDto(
    //         $receiptPrinter, $receiptRegistrationRequest, $validityTimeSpan);

    //     $receiptRegistration = $apiClient->registerReceipt($createReceiptRegistration);

    //     $this->assertNotEquals(RegistrationState::FAILED, $receiptRegistration->state);
    // }

    // public function testRegisterReceiptUsingEmailPrinter() {
    //     $settings = $this->getDemoAccountSettings();
    //     $apiClient = new HttpClient($settings->HttpClientOptions);

    //     $emailPrinterOptions = new EmailReceiptPrinterOptions("mail@example.com");
    //     $emailPrinterOptions->subject = "Váš elektronický doklad";
    //     $receiptPrinter = new EmailReceiptPrinterDto($emailPrinterOptions);

    //     $cashRegisterCode = $settings->cashRegisterCode;
    //     $externalId = "e52ff4d1-f2ed-4493-9e9a-a73739b1ba23";

    //     $item = new ReceiptRegistrationItemDto(
    //         ReceiptItemType::POSITIVE,
    //         "Coca Cola 0.25l",
    //         1.29,
    //         20.00,
    //         new QuantityDto(2, "ks"),
    //         2.58
    //     );

    //     $payment = new ReceiptRegistrationPaymentDto(2.58, "Hotovosť");

    //     $receiptRegistrationRequest = new Receipts\CreateReceiptRegistrationRequestDto(
    //         ReceiptType::CASH_REGISTER,
    //         $cashRegisterCode,
    //         $externalId,
    //         [$item],
    //         [$payment]
    //     );

    //     $receiptRegistrationRequest->headerText = "www.ninedigit.sk";
    //     $receiptRegistrationRequest->footerText = "Ďakujeme za váš nákup.";

    //     $validityTimeSpan = 1;

    //     $createReceiptRegistration = new CreateReceiptRegistrationDto(
    //         $receiptPrinter, $receiptRegistrationRequest, $validityTimeSpan);

    //     $receiptRegistration = $apiClient->registerReceipt($createReceiptRegistration);

    //     $this->assertNotEquals(RegistrationState::FAILED, $receiptRegistration->state);
    // }

    // public function testRegisterReceiptUsingPdfPrinter() {
    //     $settings = $this->getDemoAccountSettings();
    //     $apiClient = new HttpClient($settings->HttpClientOptions);

    //     $pdfPrinterOptions = new PdfReceiptPrinterOptions();
    //     $receiptPrinter = new PdfReceiptPrinterDto($pdfPrinterOptions);

    //     $cashRegisterCode = $settings->cashRegisterCode;
    //     $externalId = "e52ff4d1-f2ed-4493-9e9a-a73739b1ba23";

    //     $item = new ReceiptRegistrationItemDto(
    //         ReceiptItemType::POSITIVE,
    //         "Coca Cola 0.25l",
    //         1.29,
    //         20.00,
    //         new QuantityDto(2, "ks"),
    //         2.58
    //     );

    //     $payment = new ReceiptRegistrationPaymentDto(2.58, "Hotovosť");

    //     $receiptRegistrationRequest = new Receipts\CreateReceiptRegistrationRequestDto(
    //         ReceiptType::CASH_REGISTER,
    //         $cashRegisterCode,
    //         $externalId,
    //         [$item],
    //         [$payment]
    //     );

    //     $receiptRegistrationRequest->headerText = "www.ninedigit.sk";
    //     $receiptRegistrationRequest->footerText = "Ďakujeme za váš nákup.";

    //     $validityTimeSpan = 1;

    //     $createReceiptRegistration = new CreateReceiptRegistrationDto(
    //         $receiptPrinter, $receiptRegistrationRequest, $validityTimeSpan);

    //     $receiptRegistration = $apiClient->registerReceipt($createReceiptRegistration);

    //     $this->assertNotEquals(RegistrationState::FAILED, $receiptRegistration->state);
    // }
}
