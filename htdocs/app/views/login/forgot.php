<?php require APPROOT . '/views/common/header.php'; ?>

<h2>Get a New Password</h2>

<form action="<?php echo URLROOT . '/login/forgot' ?>" method="post">
    <div>
        <label for="email"><sup>*</sup>Email: </label>
        <input id="email" type="email" name="email" value="<?php echo $email; ?>" placeholder="email">
    </div>
    <div>
        <input type="submit" value="Submit">
    </div>
</form>

<?php require APPROOT . '/views/common/footer.php'; ?>
