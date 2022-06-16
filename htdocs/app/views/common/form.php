<form action="<?php echo URLROOT . '/users/' . $action; ?>" method="post">
    <div>
        <label for="username"><sup>*</sup>Username: </label>
        <input id="username" type="text" name="username" value="<?php echo $username; ?>" placeholder="username">
    </div>
    
    <div>
        <label for="email"><sup>*</sup>Email: </label>
        <input id="email" type="email" name="email" value="<?php echo $email; ?>" placeholder="email">
    </div>

    <div>
        <label for="password"><sup>*</sup>Password: </label>
        <input id="password" type="password" name="password" value="<?php echo $password; ?>" placeholder="Password">
    </div>

    <div>
        <label for="pwdConfirm"><sup>*</sup>Confirm Password: </label>
        <input id="pwdConfirm" type="password" name="pwdConfirm" value="<?php echo $pwdConfirm; ?>" placeholder="Confirm Password">
    </div>

    <div>
        <label for="pushNotif"><sup>*</sup>Email Notifications </label>
        <input id="pushNotif" type="checkbox"  
        <?php if(isset($pushNotif)) echo $pushNotif; ?> name="pushNotif">
    </div>

    <div>
        <input type="submit" value="Submit">
    </div>
</form>
