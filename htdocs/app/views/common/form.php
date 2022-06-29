<form action="<?php echo URLROOT . '/users/' . $action; ?>" method="post" class="column is-half">
    <div class="field">
        <label for="username" class="label"><sup>*</sup>Username: </label>
        <input id="username" type="text" name="username" value="<?php echo $username; ?>" placeholder="username" class="control input">
    </div>
    
    <div class="field">
        <label for="email" class="label"><sup>*</sup>Email: </label>
        <input id="email" type="email" name="email" value="<?php echo $email; ?>" placeholder="email" class="control input">
    </div>

    <div class="field">
        <label for="password" class="label"><sup>*</sup>Password: </label>
        <input id="password" type="password" name="password" value="<?php echo $password; ?>" placeholder="Password" class="control input">
    </div>

    <div class="field">
        <label for="pwdConfirm" class="label"><sup>*</sup>Confirm Password: </label>
        <input id="pwdConfirm" type="password" name="pwdConfirm" value="<?php echo $pwdConfirm; ?>" placeholder="Confirm Password" class="control input">
    </div>

    <div class="field">
        <label for="pushNotif" class="checkbox">Email Notifications </label>
        <input id="pushNotif" type="checkbox"  
        <?php if(isset($pushNotif)) echo $pushNotif; ?> name="pushNotif">
    </div>

    <div class="field">
        <input type="submit" value="Submit" class="control button is-primary">
    </div>
</form>
