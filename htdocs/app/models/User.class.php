<?php

class User extends Model
{
    public          $errors = [];

    public function __construct()
    {
    }

    public function new($data)/// RENAME ACTION TO NEW!!!
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        // Fill errors array (if there's any errors in the form) 
        $this->validateRegisterForm();

        // No errors in the form
        if (empty($this->errors)) {
            // Check that the email doesn't exist in the database!!!
            if ($this->findByEmail($this->email)) {
                array_push($this->errors, 'user with that email already exists');
                return false;
            }
            $pwd_hash = password_hash($this->password, PASSWORD_DEFAULT);
            $notif = empty($_POST['pushNotif']) ? '' : 'checked';
            $db = static::getDB();
            $sql = 'INSERT INTO users (username, email, pwd_hash, push_notif)
                    VALUES (:username, :email, :pwd_hash, :push_notif)';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':pwd_hash', $pwd_hash, PDO::PARAM_STR);
            $stmt->bindValue(':push_notif', $notif, PDO::PARAM_STR);
            return $stmt->execute();
        } else
            return false;
    }

    public function edit($data, $id)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
        // Fill errors array (if there's any errors in the form) 
        $this->validateRegisterForm();

        // No errors in the form
        if (empty($this->errors)) {
            $pwd_hash = password_hash($this->password, PASSWORD_DEFAULT);
            $notif = isset($this->pushNotif) ? 'checked' : '';

            $db = static::getDB();

            // Update user row
            $sql = 'UPDATE users
                    SET username    = :username,
                        email       = :email,
                        pwd_hash    = :pwd_hash,
                        push_notif  = :push_notif
                    WHERE id = :id';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':username',   $this->username,    PDO::PARAM_STR);
            $stmt->bindValue(':email',      $this->email,       PDO::PARAM_STR);
            $stmt->bindValue(':pwd_hash',   $pwd_hash,          PDO::PARAM_STR);
            $stmt->bindValue(':push_notif', $notif,             PDO::PARAM_STR);
            $stmt->bindValue(':id',         $id,                PDO::PARAM_INT);
            return $stmt->execute();
        } else
            return false;
    }

    public function validateRegisterForm()
    {
        if (empty($this->username))
            $this->errors['username_err'] = 'name is required';
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false)
            $this->errors['email_err'] = 'email is required';
        if (empty($this->password))
            $this->errors['password_err'] = 'password is required';
        else if (strlen($this->password) < 6)
            $this->errors['pwd_len_err'] = 'password must be at least 6 characters long';
        else if (preg_match('/.*[a-z]+.*/i', $this->password) == 0)
            $this->errors['pwd_letter_err'] = 'password needs at least 1 letter';
        else if (preg_match('/.*\d+.*/i', $this->password) == 0)
            $this->errors['pwd_digit_err'] = 'password needs at least 1 number';
        else if ($this->password != $this->pwdConfirm)
            $this->errors['pwd_confirm_err'] = "passwords don't match";
    }

    public function validateLoginForm()
    {
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false)
            $this->errors['email_err'] = 'email is required';
        if (empty($this->password))
            $this->errors['password_err'] = 'password is required';
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
                $this->errors['pwd_err'] = 'wrong password';
                return false;
            }
            // else if ($foundUser->confirmed == false) {
            //     $this->errors['email_confirm'] = 'please confirm your account';
            //     return false;
            // }
            else {
                return $foundUser;
            }
        } else {
            $this->errors['email_err'] = 'user does not exist';
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