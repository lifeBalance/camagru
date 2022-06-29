<?php require APPROOT . '/views/common/header.php'; ?>

<section class="section">
    <?php require APPROOT . '/views/common/flashes.php'; ?>

    <h2 class="title is-2 columns is-centered">Login</h2>
    <div class="columns is-centered">
    <form action="<?php echo URLROOT; ?>/login/new" method="post" class="column is-half">
        <div class="field">
            <label for="email" class="label"><sup>*</sup>Email: </label>
            <input type="email" name="email" value="<?php echo $email; ?>" class="control input">
        </div>

        <div class="field">
            <label for="password" class="label"><sup>*</sup>Password: </label>
            <input type="password" name="password" value="<?php echo $password; ?>" class="control input">
        </div>

        <div class="columns is-vcentered">
            <div class="control column is-half">
                <input type="submit" value="Login" class="control button is-primary">
            </div>
                
            <div class="control column is-half">
                <a href="<?php echo URLROOT; ?>/login/forgot" >Forgot your password?</a>
            </div>
        </div>
    </form>
    </div>
</section>

<?php require APPROOT . '/views/common/footer.php'; ?>
