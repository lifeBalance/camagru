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
    public static function send($data)
    {
        $href = URLROOT . "/{$data['controller']}/{$data['action']}";
        // Some emails may not use a token (hence below)
        if (isset($data['token']))
            $href .= "/{$data['token']}";
        $link = '<a href="' .$href . '">here</a>';
        $message = "Click $link to: <b>{$data['subject']}</b>\r\n";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <camagru69@outlook.com>' . "\r\n";

        return mail(
            '<' . $data['address'] . '>',
            $data['subject'],
            $message,
            $headers);
    }

    static function notify($data)
    {
        $message = "<b>{$data['username']}</b> commented on one of your posts\r\n";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <camagru69@outlook.com>' . "\r\n";

        return mail(
            '<' . $data['address'] . '>',
            $data['subject'],
            $message,
            $headers);
    }
}