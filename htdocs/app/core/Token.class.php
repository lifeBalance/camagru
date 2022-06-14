<?php

class Token
{
    protected $token;

    public function __construct($token_value = null)
    {
        if ($token_value)
            $this->token = $token_value;
        else
            $this->token = bin2hex(random_bytes(16));  // 128 bit = 16 bytes = up to 32 hex characters (max)
    }

    public function getToken()
    {
        return $this->token;
    }
    
    public function getHash()
    {
        return hash_hmac('sha_256', $this->token, SECRET_HASHING_KEY);  // sha256 = 64 characters
    }
}