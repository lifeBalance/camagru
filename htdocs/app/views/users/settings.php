<?php require APPROOT . '/views/common/header.php'; ?>

<section class="section">
    <div class="columns is-centered">
        <div class="column is-half">
            <?php require APPROOT . '/views/common/flashes.php'; ?>

            <h2 class="title is-2">Your Settings</h2>
            <form action="<?php echo URLROOT . '/users/settings' ?>" method="post">
                <div class="field">
                    <label for="username" class="label">Username: </label>
                    <input id="username" type="text" name="username" value="<?php echo $username; ?>" placeholder="username" class="control input" autocomplete="username">
                </div>

                <div class="field">
                    <label for="email" class="label">Email: </label>
                    <input id="email" type="email" name="email" value="<?php echo $email; ?>" placeholder="email" class="control input" autocomplete="current-email">
                </div>

                <div class="field">
                    <label for="gravatar" class="label">Gravatar: </label>
                    <input id="gravatar" type="text" name="gravatar" value="<?php echo $gravatar; ?>" placeholder="gravatar" class="control input">
                </div>

                <div class="field">
                    <label for="password" class="label">New Password: </label>
                    <input id="password" type="password" name="password" placeholder="Password" class="control input" autocomplete="new-password">
                </div>

                <div class="field">
                    <label for="pwdConfirm" class="label">Confirm New Password: </label>
                    <input id="pwdConfirm" type="password" name="pwdConfirm" placeholder="Confirm Password" class="control input" autocomplete="new-password">
                </div>

                <div class="columns is-vcentered">
                    <div class="control column is-half">
                        <input type="submit" value="Submit" class="control button is-primary">
                    </div>
                    <div class="control column is-half">
                        <label for="pushNotif" class="checkbox">Email Notifications </label>
                        <input id="pushNotif" type="checkbox"  
                        <?php if($pushNotif) echo 'checked'; ?> name="pushNotif">
                    </div>
                </div>
            </form>
        </div><!-- column -->
    </div><!-- columns -->
</section>

<?php require APPROOT . '/views/common/footer.php'; ?>
