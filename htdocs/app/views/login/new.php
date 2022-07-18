<?php require APPROOT . '/views/common/header.php'; ?>

<section class="section">
    <div class="columns is-centered">
        <div class="column is-half">
            <?php require APPROOT . '/views/common/flashes.php'; ?>

            <h2 class="title">Login</h2>

            <form action="<?php echo URLROOT; ?>/login/new" method="post">
                <div class="field">
                    <label for="email" class="label"><sup>*</sup>Email: </label>
                    <input type="email" name="email" value="<?php echo $email; ?>" class="control input" autocomplete="current-email" required>
                </div>
        
                <div class="field">
                    <label for="password" class="label"><sup>*</sup>Password: </label>
                    <input type="password" name="password" value="<?php echo $password; ?>" class="control input" autocomplete="current-password" placeholder="Between 6-255 characters." required pattern="[0-9a-z]{6,255}">
                    <p class="pwd-helper is-size-7" hidden><sup>*</sup>Must contain digits and lowercase-letters (6-255).</p>
                </div>

                <div class="columns is-vcentered">
                    <div class="control column is-half">
                        <input type="submit" value="Login" class="control button is-primary">
                        <a class="control button is-white" href="/">Cancel</a>
                    </div>
        
                    <div class="control column is-half">
                        <a href="<?php echo URLROOT; ?>/login/forgot" >Forgot your password?</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require APPROOT . '/views/common/footer.php'; ?>
