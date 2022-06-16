<?php

session_start();

// phpinfo();
require_once '../app/core/bootstrap.php';

$router = new Router();
// $tos = [
//     "42jaiver42@gmail.com",
//     "yagele7071@qqhow.com",
//     "corzerurki@vusra.com",
// ];
// $subject = "My subject";
// $txt = "Hello world!";
// $headers = "From: webmaster@example.com" . "\r\n" .
// "CC: somebodyelse@example.com";

// foreach ($tos as $to) {
//     if (mail($to,$subject,$txt,$headers))
//         echo "Success: $to";
//     else
//         echo "Woops: $to<br>";
// }
// echo 'hi';