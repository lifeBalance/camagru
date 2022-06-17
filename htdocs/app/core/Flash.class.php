<?php

class Flash
{
    public static function addFlashes($msgs = [])
    {
        if (!isset($_SESSION['flashes']))
            $_SESSION['flashes'] = [];
        foreach ($msgs as $msg => $type)
            $_SESSION['flashes'][$msg] = $type;
    }

    public static function getFlashes()
    {
        if (isset($_SESSION['flashes']))
            $flashes = $_SESSION['flashes'];
        $_SESSION['flashes'] = [];
        return $flashes;
    }

    public static function beFlashes()
    {
        return !empty($_SESSION['flashes']);
    }
}