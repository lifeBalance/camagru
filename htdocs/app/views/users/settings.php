<?php require APPROOT . '/views/common/header.php'; ?>

<section class="section">
    <div class="columns is-centered">
        <div class="column is-half">
            <?php require APPROOT . '/views/common/flashes.php'; ?>

            <h2 class="title is-2">Your Settings</h2>
            <form action="<?php echo URLROOT . '/users/settings' ?>" method="post">
                <div class="field">
                    <label for="username" class="label">Username: </label>
                    <input id="username" type="text" name="username" value="<?php echo $username; ?>" placeholder="username" class="control input" autocomplete="username" required minlength="1" maxlength="50">
                    <p class="is-size-7" id="counter" ><span class="count">50</span> characters left.</p>
                </div>

                <div class="field">
                    <label for="email" class="label">Email: </label>
                    <input id="email" type="email" name="email" value="<?php echo $email; ?>" placeholder="email" class="control input" autocomplete="current-email" required>
                </div>

                <div class="field">
                    <label for="gravatar" class="label">Gravatar: </label>
                    <input id="gravatar" type="url" name="gravatar" value="<?php echo $gravatar; ?>" placeholder="Between 0-255 characters." class="control input" maxlength="255">
                    <p class="grav-helper is-size-7" hidden>Must be a valid url (Max. 255 characters).</p>
                </div>

                <div class="field">
                    <label for="password" class="label">New Password: </label>
                    <input id="password" type="password" name="password" placeholder="Between 6-255 characters." class="control input" autocomplete="new-password" pattern="[0-9a-z]{6,255}">
                    <p class="pwd-helper is-size-7" hidden><sup>*</sup>Must contain digits and lowercase-letters (6-255).</p>
                </div>

                <div class="field">
                    <label for="pwdConfirm" class="label">Confirm New Password: </label>
                    <input id="pwdConfirm" type="password" name="pwdConfirm" placeholder="Between 6-255 characters." class="control input" autocomplete="new-password" pattern="[0-9a-z]{6,255}">
                    <p class="pwd-helper2 is-size-7" hidden><sup>*</sup>Must contain digits and lowercase-letters (6-255).</p>
                    <p class="pwd-match2 is-size-7" hidden><sup>*</sup>Passwords don't match.</p>
                </div>

                <div class="columns is-vcentered">
                    <div class="control column is-half">
                        <input type="submit" value="Submit" class="control button is-primary">
                        <a class="control button is-white" href="/">Cancel</a>
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
