<?php require APPROOT . '/views/common/header.php'; ?>

<h2><?php echo $title; ?></h2>
<!-- flash messages -->
<ul>
    <?php if(isset($errors)) : ?>
        <? foreach ($errors as $k => $v) : ?>
            <? echo '<li>' . $v . '</li>'; ?>
        <? endforeach; ?>
    <?php endif; ?>
    <?php if(isset($success)) : ?>
        <? echo '<li>' . $success . '</li>'; ?>
    <?php endif; ?>
</ul>
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

<?php require APPROOT . '/views/common/footer.php'; ?>
