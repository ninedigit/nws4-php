<?php

namespace NineDigit\NWS4\Tests;

use DateTime;
use NineDigit\NWS4\LocalDateTimeService;
use PHPUnit\Framework\TestCase;


final class LocalDateTimeServiceTest extends TestCase {
    public function testGetNowUtc() {
        $localDateTimeService = new LocalDateTimeService();
        $dateTime = $localDateTimeService->getNowUtc();

        $this->assertInstanceOf(DateTime::class, $dateTime);
    }
}