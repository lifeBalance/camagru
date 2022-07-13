<?php require APPROOT . '/views/common/header.php'; ?>

<section class="section">
    <div class="columns is-centered">
        <div class="column is-half">
            <?php require APPROOT . '/views/common/flashes.php'; ?>
            <h2 class="title">Enter your New Password</h2>
            <form action="<?php echo URLROOT . '/login/reset/' . $token ?>" method="post">
                <div class="field">
                    <label for="password" class="label"><sup>*</sup>Password: </label>
                    <input id="password" type="password" name="password" value="<?php echo $password; ?>" placeholder="Password" class="control input">
                </div>

                <div class="field">
                    <label for="pwdConfirm" class="label"><sup>*</sup>Confirm Password: </label>
                    <input id="pwdConfirm" type="password" name="pwdConfirm" value="<?php echo $pwdConfirm; ?>" placeholder="Confirm Password" class="control input">
                </div>

                <div class="field">
                    <input type="submit" value="Submit" class="control button is-primary">
                </div>
            </form>
        </div><!-- column -->
    </div><!-- columns -->
</section>
<?php require APPROOT . '/views/common/footer.php'; ?>
