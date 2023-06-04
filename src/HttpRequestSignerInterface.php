<?php

namespace NineDigit\NWS4;

interface HttpRequestSignerInterface
{
    public function sign(HttpRequestMessage $request);
}
