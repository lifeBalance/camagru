<?php

class Token
{
    static function new()
    {
        $token = bin2hex(random_bytes(16));  // 128 bit = 16 bytes = up to 32 hex characters (max)
        return hash_hmac('sha256', $token, SECRET_HASHING_KEY);  // sha256 = 64 characters
    }
}