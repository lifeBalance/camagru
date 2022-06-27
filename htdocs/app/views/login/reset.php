<?php require APPROOT . '/views/common/header.php'; ?>

<h2>Enter your New Password</h2>

<form action="<?php echo URLROOT . '/login/reset/' . $token ?>" method="post">
<div>
        <label for="password"><sup>*</sup>Password: </label>
        <input id="password" type="password" name="password" value="<?php echo $password; ?>" placeholder="Password">
    </div>

    <div>
        <label for="pwdConfirm"><sup>*</sup>Confirm Password: </label>
        <input id="pwdConfirm" type="password" name="pwdConfirm" value="<?php echo $pwdConfirm; ?>" placeholder="Confirm Password">
    </div>
    <div>
        <input type="submit" value="Submit">
    </div>
</form>

<?php require APPROOT . '/views/common/footer.php'; ?>
