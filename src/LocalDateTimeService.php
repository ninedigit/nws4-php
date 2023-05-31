<?php

namespace NineDigit\NWS4;

class LocalDateTimeService implements DateTimeServiceInterface {
  public function getNowUtc(): \DateTime {
    return \DateTime::createFromFormat("U.u", microtime(TRUE), new \DateTimeZone("UTC"));
  }
}

?>