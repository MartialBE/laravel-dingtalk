<?php

if (! function_exists('generate_sign')) {

    function generate_sign(string $secret, int $timestamp, bool $urlencode = false)
    {
        $data = sprintf("%s\n%s", $timestamp, $secret);

        $hash = hash_hmac('sha256', $data, $secret, true);

        $sign = base64_encode($hash);
        return $urlencode ?  urlencode($sign) : $sign;
    }
}
