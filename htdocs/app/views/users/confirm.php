<?php require APPROOT . '/views/common/header.php'; ?>

<h2>Request new confirmation email</h2>

<form action="<?php echo URLROOT; ?>/users/confirm" method="post">
    <div>
        <label for="email"><sup>*</sup>Email: </label>
        <input type="email" name="email" value="<?php echo $email; ?>">
    </div>

    <div>
        <label for="password"><sup>*</sup>Password: </label>
        <input type="password" name="password" value="<?php echo $password; ?>">
    </div>

    <div>
        <input type="submit" value="Confirm Account">
    </div>
</form>

<?php require APPROOT . '/views/common/footer.php'; ?>
