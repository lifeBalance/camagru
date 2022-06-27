<?php

class Token extends Model
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
        return hash_hmac('sha256', $this->token, SECRET_HASHING_KEY);  // sha256 = 64 characters
    }


    /**
     * Update a user's token in the db with a newly generated one. Locate the
     * user in the db using its 'email' argument.
     * 
     * @param email  The (sanitized) user's email.
     * 
     * @return Mixed The token itself/false;
     */
    static function generateToken($email)
    {
        $token = new Token();
        $hash = $token->getHash();

        $db = static::getDB();
        $sql = 'UPDATE users
                SET     token = :token
                WHERE   email = :email';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':token', $hash, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        if ($stmt->execute())
            return $hash;
        else
            return false;
    }
}