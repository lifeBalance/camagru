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
                array_push($this->errors, $this->errors['user with that email already exists'] = 'error');
                return false;
            }
            $pwd_hash = password_hash($data['password'], PASSWORD_DEFAULT);
            if (isset($data['pushNotif']) && $data['pushNotif'] == 'on')
                $notif = true;
            else
                $notif = false;
            $db = static::getDB();
            $sql = 'INSERT INTO users (username, email, pwd_hash, push_notif)
                    VALUES (:username, :email, :pwd_hash, :push_notif)';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':username', $data['username'], PDO::PARAM_STR);
            $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindValue(':pwd_hash', $pwd_hash, PDO::PARAM_STR);
            $stmt->bindValue(':push_notif', $notif, PDO::PARAM_BOOL);
            $stmt->execute();
            return $this->findByEmail($data['email']);
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
            if (isset($data['pushNotif']) && $data['pushNotif'] == 'on')
                $notif = true;
            else
                $notif = false;

            $db = static::getDB();

            // Update user row
            $sql = 'UPDATE users
                    SET username    = :username,
                        email       = :email,
                        pwd_hash    = :pwd_hash,
                        push_notif  = :push_notif
                    WHERE id = :id';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':username',   $data['username'],  PDO::PARAM_STR);
            $stmt->bindValue(':email',      $data['email'],     PDO::PARAM_STR);
            $stmt->bindValue(':pwd_hash',   $pwd_hash,          PDO::PARAM_STR);
            $stmt->bindValue(':push_notif', $notif,             PDO::PARAM_BOOL);
            $stmt->bindValue(':id',         $id,                PDO::PARAM_INT);
            return $stmt->execute();
        } else
            return false;
    }

    public function validateRegisterForm($data)
    {
        if (empty($data['username']))
            $this->errors['name is required'] = 'error';
        if (strlen($data['username']) > 50)
            $this->errors['username max. 50 characters long'] = 'error';
        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false)
            $this->errors['email is required'] = 'error';
        if (empty($data['password']))
            $this->errors['password is required'] = 'error';
        else if (strlen($data['password']) < 6)
            $this->errors['password must be at least 6 characters long'] = 'error';
        else if (preg_match('/.*[a-z]+.*/i', $data['password']) == 0)
            $this->errors['password needs at least 1 letter'] = 'error';
        else if (preg_match('/.*\d+.*/i', $data['password']) == 0)
            $this->errors['password needs at least 1 number'] = 'error';
        else if ($data['password'] != $data['pwdConfirm'])
            $this->errors["passwords don't match"] = 'error';
    }

    public function validateLoginForm()
    {
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false)
            $this->errors['email is required'] = 'error';
        if (empty($this->password))
            $this->errors['password is required'] = 'error';
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
                $this->errors['wrong password'] = 'error';
                return false;
            }
            else {
                return $foundUser;
            }
        } else {
            $this->errors['user does not exist'] = 'error';
            return false;
        }
    }

    public function findByEmail($email)
    {
        // var_dump($email);
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
    
    public function generateToken($email)
    {
        $token = new Token();
        $hash = $token->getHash();

        $db = static::getDB();
        $sql = 'UPDATE users
                SET confirm_hash = :confirm_hash
                WHERE email = :email';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':confirm_hash', $hash, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        if ($stmt->execute())
            return $hash;
        else
            return false;
    }

    public function verifyToken($token)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE confirm_hash = ?");
        $stmt->execute([$token]);
        if ($stmt->fetch(PDO::FETCH_OBJ))
        {
            $sql = 'UPDATE users
                    SET confirmed = :confirmed
                    WHERE confirm_hash = :confirm_hash';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':confirmed', true, PDO::PARAM_BOOL);
            $stmt->bindValue(':confirm_hash', $token, PDO::PARAM_STR);
            return $stmt->execute();
        }
    }
}