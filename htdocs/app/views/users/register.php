<?php require APPROOT . '/views/common/header.php'; ?>

<h2>Register</h2>
<!-- flash messages -->
<ul>
    <?php if(isset($errors)) : ?>
        <? foreach ($errors as $k => $v) : ?>
            <li>
                <p><? echo $v; ?></p>
            </li>
        <? endforeach; ?>
    <?php endif; ?>
</ul>

<form action="<? echo URLROOT; ?>/users/register" method="post">
    <div>
        <label for="username"><sup>*</sup>Username: </label>
        <input id="username" type="text" name="username" value="<?php echo $username; ?>" placeholder="username">
    </div>
    
    <div>
        <label for="email"><sup>*</sup>Email: </label>
        <input id="email" type="email" name="email" value="<?php echo $email; ?>" placeholder="email">
    </div>

    <div>
        <label for="password"><sup>*</sup>Password: </label>
        <input id="password" type="password" name="password" value="<?php echo $password; ?>" placeholder="Password">
    </div>

    <div>
        <label for="pwdConfirm"><sup>*</sup>Confirm Password: </label>
        <input id="pwdConfirm" type="password" name="pwdConfirm" value="<?php echo $pwdConfirm; ?>" placeholder="Confirm Password">
    </div>

    <div>
        <input type="submit" value="Register">
    </div>
</form>

<?php require APPROOT . '/views/common/footer.php'; ?>
