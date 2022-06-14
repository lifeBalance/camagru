<?php

class User extends Model
{
    public          $errors = [];

    public function __construct() {}

    public function new($data)/// RENAME ACTION TO NEW!!!
    {
        // Fill errors array (if there's any errors in the form) 
        $this->validateRegisterForm($data);

        // No errors in the form
        if (empty($this->errors)) {
            // Check that the email doesn't exist in the database!!!
            if ($this->findByEmail($data['email'])) {
                array_push($this->errors, 'user with that email already exists');
                return false;
            }
            $pwd_hash = password_hash($data['password'], PASSWORD_DEFAULT);
            if (isset($data['pushNotif']) && strlen($data['pushNotif']) > 0)
                $notif =  'checked';
            else
                $notif = '';
            $db = static::getDB();
            $sql = 'INSERT INTO users (username, email, pwd_hash, push_notif)
                    VALUES (:username, :email, :pwd_hash, :push_notif)';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':username', $data['username'], PDO::PARAM_STR);
            $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindValue(':pwd_hash', $pwd_hash, PDO::PARAM_STR);
            $stmt->bindValue(':push_notif', $notif, PDO::PARAM_STR);
            return $stmt->execute();
        } else
            return false;
    }

    public function edit($data, $id)
    {
        // Fill errors array (if there's any errors in the form) 
        $this->validateRegisterForm($data);

        // No errors in the form
        if (empty($this->errors)) {
            $pwd_hash = password_hash($data['password'], PASSWORD_DEFAULT);
            if (isset($data['pushNotif']) && strlen($data['pushNotif']) > 0)
                $notif =  'checked';
            else
                $notif = '';

            $db = static::getDB();

            // Update user row
            $sql = 'UPDATE users
                    SET username    = :username,
                        email       = :email,
                        pwd_hash    = :pwd_hash,
                        push_notif  = :push_notif
                    WHERE id = :id';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':username',   $data['username'],    PDO::PARAM_STR);
            $stmt->bindValue(':email',      $data['email'],       PDO::PARAM_STR);
            $stmt->bindValue(':pwd_hash',   $pwd_hash,          PDO::PARAM_STR);
            $stmt->bindValue(':push_notif', $notif,             PDO::PARAM_STR);
            $stmt->bindValue(':id',         $id,                PDO::PARAM_INT);
            return $stmt->execute();
        } else
            return false;
    }

    public function validateRegisterForm($data)
    {
        if (empty($data['username']))
            array_push($this->errors, 'name is required');
        if (strlen($data['username']) > 150)
            array_push($this->errors, 'username max. 50 characters long');
        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false)
            array_push($this->errors, 'email is required');
        if (empty($data['password']))
            array_push($this->errors, 'password is required');
        else if (strlen($data['password']) < 6)
            array_push($this->errors, 'password must be at least 6 characters long');
        else if (preg_match('/.*[a-z]+.*/i', $data['password']) == 0)
            array_push($this->errors, 'password needs at least 1 letter');
        else if (preg_match('/.*\d+.*/i', $data['password']) == 0)
            array_push($this->errors, 'password needs at least 1 number');
        else if ($data['password'] != $data['pwdConfirm'])
            array_push($this->errors, "passwords don't match");
    }

    public function validateLoginForm()
    {
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false)
            array_push($this->errors, 'email is required');
        if (empty($this->password))
            array_push($this->errors, 'password is required');
    }

    public function authenticate($data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
        // Fill errors array (if there's any errors in the form) 
        $this->validateLoginForm();
        if (!empty($this->errors))
            return false;
        $foundUser = $this->findByEmail($this->email);
        if ($foundUser) {
            if (!password_verify($this->password, $foundUser->pwd_hash)) {
                array_push($this->errors, 'wrong password');
                return false;
            }
            else {
                return $foundUser;
            }
        } else {
            array_push($this->errors, 'user does not exist');
            return false;
        }
    }

    public function findByEmail($email)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function findById($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}