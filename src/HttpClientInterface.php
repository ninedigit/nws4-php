<?php

namespace NineDigit\NWS4;

interface HttpClientInterface
{
    public function send(HttpRequestMessage $request, $sign = false): HttpResponseMessage;
}
