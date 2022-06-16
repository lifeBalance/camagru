<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">
    <title><?php echo SITENAME; ?></title>
</head>
<body>
    <?php require APPROOT . '/views/common/navbar.php'; ?>
    <hr>
    <div class="container">
        <!-- flash messages -->
        <ul>
            <?php if(Flash::beFlashes()) : ?>
                <?php $flashes = Flash::getFlashes() ?>
                <?php foreach ($flashes as $flash) : ?>
                    <?php echo '<li>' . $flash . '</li>'; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
