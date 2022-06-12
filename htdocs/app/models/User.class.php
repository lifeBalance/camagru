<?php

class User extends Model
{
    public function __construct()
    {
    }

    public function save($data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
        // Check that the email doesn't exist in the database!!!
        if (!$this->findByEmail($this->email)) {
            $pwd_hash = password_hash($this->password, PASSWORD_DEFAULT);
            $db = static::getDB();
            $sql = 'INSERT INTO users (username, email, pwd_hash)
                    VALUES (:username, :email, :pwd_hash)';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':pwd_hash', $pwd_hash, PDO::PARAM_STR);
            return $stmt->execute();
        } else
            return false;
    }

    public function authenticate($email, $password)
    {
        $foundUser = $this->findByEmail($email);
        if ($foundUser && password_verify($password, $foundUser->pwd_hash))
            return $foundUser;
        else
            return false;
    }

    public function findByEmail($email)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}