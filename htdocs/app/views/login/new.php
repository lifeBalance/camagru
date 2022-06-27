<?php require APPROOT . '/views/common/header.php'; ?>

<h2>Login</h2>

<form action="<?php echo URLROOT; ?>/login/new" method="post">
    <div>
        <label for="email"><sup>*</sup>Email: </label>
        <input type="email" name="email" value="<?php echo $email; ?>">
    </div>

    <div>
        <label for="password"><sup>*</sup>Password: </label>
        <input type="password" name="password" value="<?php echo $password; ?>">
    </div>

    <div>
        <div>
            <input type="submit" value="Login">
        </div>
        
        <div>
            <p>Not registered dawg?
            <a href="<?php echo URLROOT; ?>/users/register">Create account!</a></p>
        </div>

        <div>
            <p>Forgot your password?
            <a href="<?php echo URLROOT; ?>/users/newpwd">Request a new one!</a></p>
        </div>
        <div>
            <p>No confirmation email?
            <a href="<?php echo URLROOT; ?>/users/confirm">Request a new one!</a></p>
        </div>
    </div>
</form>

<?php require APPROOT . '/views/common/footer.php'; ?>
