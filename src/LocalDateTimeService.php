<?php

namespace NineDigit\NWS4; 

use DateTime;
use DateTimeZone;

class LocalDateTimeService implements DateTimeServiceInterface
{
    public function getNowUtc(): DateTime
    {
        $microtime = microtime(true);
        $seconds = floor($microtime);
        $microseconds = ($microtime - $seconds) * 1000000;

        // Create DateTime object from the Unix timestamp
        $datetime = new DateTime('@' . $seconds, new DateTimeZone('UTC')); 
        $datetime->setTimezone(new DateTimeZone('UTC'));

        // Manually set microseconds
        $datetime = $datetime->setTime(
            (int) $datetime->format('H'),
            (int) $datetime->format('i'),
            (int) $datetime->format('s'),
            (int) $microseconds
        );

        return $datetime;
    }
}
