<?php

namespace NineDigit\NWS4\Tests;

final class DateTimeHelper {
    public static function createUtc(int $year, int $month, int $day, int $hour, int $minute, int $second = 0, int $microsecond = 0): \DateTime {
      $date = \DateTime::createFromFormat("U.u", microtime(TRUE), new \DateTimeZone("UTC"));
      $date = $date->setDate($year, $month, $day);
      $date = $date->setTime($hour, $minute, $second, $microsecond);
  
      return $date;
    }
}
