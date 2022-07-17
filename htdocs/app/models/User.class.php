<?php

class User extends Model
{
    public          $errors = [];

    public function __construct() {}

    /**
     * Create a new user in the 'users' table:
     *      - Validate the data in the submitted "Register" form.
     *      - Fill the 'errors' array in case of invalid fields.
     *      - Check that the submitted email doesn't already exist in the db.
     *      - Hash the password before writing it.
     *
     * @param data  The (sanitized) data from the "register" form. 
     *
     * @return An associative array containing the user; or false;
     */
    public function new($data)/// RENAME ACTION TO NEW!!!
    {
        // Fill errors array (if there's any errors in the form) 
        $this->validateRegisterForm($data);

        // No errors in the form
        if (empty($this->errors)) {
            // Check that the email doesn't exist in the database!!!
            if ($this->findByEmail($data['email'])) {
                $this->errors['User with that email already exists'] = 'error';
                return false;
            }
            $pwd_hash = password_hash($data['password'], PASSWORD_DEFAULT);
            if (isset($data['pushNotif']) && $data['pushNotif'] == 'on')
                $notif = true;
            else
                $notif = false;
            $db = static::getDB();
            $sql = 'INSERT INTO users (username, email, profile_pic, pwd_hash, push_notif)
                    VALUES (:username, :email, :profile_pic, :pwd_hash, :push_notif)';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':username', $data['username'], PDO::PARAM_STR);
            $stmt->bindValue(':profile_pic', $data['gravatar'], PDO::PARAM_STR);
            $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindValue(':pwd_hash', $pwd_hash, PDO::PARAM_STR);
            $stmt->bindValue(':push_notif', $notif, PDO::PARAM_BOOL);
            $stmt->execute();
            return $this->findByEmail($data['email']);
        } else
            return false;
    }

    /**
     * Update a user row in the 'users' table with new intel from the 
     * "Settings" form:
     *      - Validate the data in the submitted "settings" form.
     *      - Fill the 'errors' array in case of invalid fields using the
     *      'validateRegisterForm' method.
     *      - Write the new user settings to db if there's no errors.
     *
     * @param data  The (sanitized) data from the "settings" form. 
     *
     * @return True/false;
     */
    public function edit($data, $id)
    {
        // Fill errors array (if there's any errors in the form) 
        $this->validateSettingsForm($data);

        // No errors in the form
        if (empty($this->errors)) {
            if (isset($data['password'])) {
                $pwd_hash = password_hash($data['password'], PASSWORD_DEFAULT);
            } else {
                $pwd_hash = $this->findById($_SESSION['user_id'])->pwd_hash;
            }
            if (isset($data['pushNotif']) && $data['pushNotif'] == 'on')
                $notif = true;
            else
                $notif = false;

            $db = static::getDB();

            // Update user row
            $sql = 'UPDATE users
                    SET username    = :username,
                        email       = :email,
                        profile_pic = :profile_pic,
                        pwd_hash    = :pwd_hash,
                        push_notif  = :push_notif
                    WHERE id = :id';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':username',   $data['username'],  PDO::PARAM_STR);
            $stmt->bindValue(':profile_pic',$data['gravatar'],  PDO::PARAM_STR);
            $stmt->bindValue(':email',      $data['email'],     PDO::PARAM_STR);
            $stmt->bindValue(':pwd_hash',   $pwd_hash,          PDO::PARAM_STR);
            $stmt->bindValue(':push_notif', $notif,             PDO::PARAM_BOOL);
            $stmt->bindValue(':id',         $id,                PDO::PARAM_INT);
            return $stmt->execute();
        } else
            return false;
    }

    public function validateSettingsForm($data)
    {
        if (empty($data['username']))
            $this->errors['Username is required'] = 'danger';
        if (strlen($data['username']) > 50)
            $this->errors['Username too long (max. 50 characters)'] = 'danger';
        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false)
            $this->errors['Valid email is required'] = 'danger';
        if (strlen($data['gravatar']) > 255)
            $this->errors['Gravatar too long (max. 255 characters)'] = 'danger';
        // Verify password errors only if a password was submitted
        if (isset($data['password']))
        {
            if (strlen($data['password']) < 6 || strlen($data['password']) > 255)
                $this->errors['New password must be between 6-255 characters long'] = 'danger';
            if (preg_match('/.*[a-z]+.*/i', $data['password']) == 0)
                $this->errors['New password needs at least 1 letter'] = 'danger';
            if (preg_match('/.*\d+.*/i', $data['password']) == 0)
                $this->errors['New password needs at least 1 number'] = 'danger';
            if ($data['password'] != $data['pwdConfirm'])
                $this->errors["Passwords don't match"] = 'danger';
        }
    }
    /**
     * Helper method to validate the (sanitized) fields in either the "register"
     * or the "settings" forms. fill the 'errors' property array in case of
     * errors.
     * 
     * @param data  The (sanitized) data from either the "register" or
     *              "settings" form. 
     */
    public function validateRegisterForm($data)
    {
        if (empty($data['username']))
            $this->errors['Username is required'] = 'danger';
        if (strlen($data['username']) > 50)
            $this->errors['Username too long (max. 50 characters)'] = 'danger';
        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false)
            $this->errors['Valid email is required'] = 'danger';
        if (empty($data['password']))
            $this->errors['Password is required'] = 'danger';
        if (strlen($data['password']) < 6 || strlen($data['password']) > 255)
            $this->errors['Password must be between 6-255 characters long'] = 'danger';
        if (preg_match('/.*[a-z]+.*/i', $data['password']) == 0)
            $this->errors['Password needs at least 1 letter'] = 'danger';
        if (preg_match('/.*\d+.*/i', $data['password']) == 0)
            $this->errors['Password needs at least 1 number'] = 'danger';
        if ($data['password'] != $data['pwdConfirm'])
            $this->errors["Passwords don't match"] = 'danger';
        if (strlen($data['gravatar']) > 255)
            $this->errors['Gravatar too long (max. 255 characters)'] = 'danger';
    }

    /**
     * Helper method to validate the (sanitized) fields in either the "login"
     * form. Fill the 'errors' property array in case of errors.
     */
    public function validateLoginForm()
    {
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false)
            $this->errors['Email is required'] = 'danger';
        if (empty($this->password))
            $this->errors['Password is required'] = 'danger';
    }

    /**
     * Helper method to validate the (sanitized) fields in either the "login"
     * form. Fill the 'errors' property array in case of errors.
     */
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
                $this->errors['Wrong password'] = 'danger';
                return false;
            }
            else {
                return $foundUser;
            }
        } else {
            $this->errors['User does not exist'] = 'danger';
            return false;
        }
    }

    /**
     * Helper method to find a user using her email.
     * 
     * @param email  The (sanitized) email.
     * 
     * @return Mixed Associative array containing the user; or false;
     */
    public function findByEmail($email)
    {
        // var_dump($email);
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Helper method to find a user using her id.
     * 
     * @param email  The (sanitized) id.
     * 
     * @return Mixed Associative array containing the user; or false;
     */
    public function findById($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Helper method to update a user's password using its 'pwd' argument.
     * Locate the user in the db using its 'token' argument.
     * 
     * @param token  The (sanitized) hashed token.
     * @param pwd  The (sanitized) NEW password.
     * 
     * @return Boolean True/false;
     */
    public function updatePwd($token, $pwd)
    {
        $db = static::getDB();
        $sql = 'UPDATE  users
                SET     pwd_hash    = :pwd_hash
                WHERE   token       = :token';
        $stmt = $db->prepare($sql);
        $pwd_hash = password_hash($pwd, PASSWORD_DEFAULT);
        $stmt->bindValue(':pwd_hash', $pwd_hash, PDO::PARAM_STR);
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // /**
    //  * Update a user's token in the db with a newly generated one. Locate the
    //  * user in the db using its 'email' argument.
    //  * 
    //  * @param email  The (sanitized) user's email.
    //  * 
    //  * @return Mixed The token itself/false;
    //  */
    public function generateToken($email)
    {
        // $token = new Token();
        $hash = Token::new();

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

    /**
     * Use the token (contained in 'params') to confirm the users account
     * if a match is found in the db.
     *
     * @param params Array of values in the query string passed down 
     *               by the router.
     *
     * @return Bool True/false;
     */
    public function verifyToken($params)
    {
        $token = $params[0];
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE token = ?");
        $stmt->execute([$token]);
        if ($stmt->fetch(PDO::FETCH_OBJ))
        {
            $sql = 'UPDATE  users
                    SET     confirmed   = :confirmed
                    WHERE   token       = :token';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':confirmed', true, PDO::PARAM_BOOL);
            $stmt->bindValue(':token', $token, PDO::PARAM_STR);
            return $stmt->execute();
        }
    }

    /**
     * Check that the token (contained in 'params') exists in the db.
     *
     * @param params Array of values in the query string passed down 
     *               by the router.
     *
     * @return Bool True/false;
     */
    public function validToken($token)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE token = ?");
        $stmt->execute([$token]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Helper method to set the value of the `confirmed' column in the db. confirm the users account
     * if a match is found in the db.
     * 
     * @param email Email of the user.
     * @param yesNo The value of the 'confirmed' column.
     * 
     * @return Bool True/false;
     */
    public function confirmEmail($email, $yesNo)
    {
        $db = static::getDB();
        $sql = 'UPDATE  users
                SET     confirmed   = :confirmed
                WHERE   email       = :email';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':confirmed', $yesNo, PDO::PARAM_BOOL);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        return $stmt->execute();
    }
}