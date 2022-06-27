<?php

class Mail
{
    /**
     * Send emails to users.
     *
     * @param email     Email address of the user. 
     * @param subject   Subject of the email.
     * @param action    One of: 
     *
     * @return true or false
     */
    public static function send($email, $subject, $controller, $action)
    {
        $subject = $subject;
        $token = Token::generateToken($email);
        $message = 'Click <a href="http://localhost/' . "$controller/$action/$token" . '">here</a> to: <b>' . $subject . "</b>.\r\n";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <camagru69@outlook.com>' . "\r\n";

        return mail("<$email>", $subject, $message, $headers);
    }
}