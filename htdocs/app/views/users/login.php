<?php require APPROOT . '/app/views/common/header.php'; ?>

<h2>Login</h2>
<form action="<? echo URLROOT; ?>/users/login" method="post">
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
    </div>
</form>

<?php require APPROOT . '/app/views/common/footer.php'; ?>
