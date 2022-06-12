<?php
class User extends Model
{
    private $db;

    public function __construct()
    {
        $this->db = static::getDB();
    }

    public function getUsers()
    {
        $stmt = $this->db->query('SELECT * FROM users');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function authenticate($email, $password)
    {
        $foundUser = $this->findByEmail($email);
        if ($foundUser && $foundUser->password === $password)
            return $foundUser;
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}