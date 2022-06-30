<?php require APPROOT . '/views/common/header.php'; ?>

<section class="section">
    <?php require APPROOT . '/views/common/flashes.php'; ?>

    <h2 class="title is-2 columns is-centered">Request New Password</h2>
    <div class="columns is-centered">
    <form action="<?php echo URLROOT . '/login/forgot' ?>" method="post" class="column is-half">
        <div class="field">
            <label for="email" class="label"><sup>*</sup>Email: </label>
            <input id="email" type="email" name="email" value="<?php echo $email; ?>" placeholder="email" class="control input">
        </div>

        <div class="columns is-vcentered">
            <div class="control column is-half">
                <input type="submit" value="Submit" class="control button is-primary">
            </div>

            <div class="control column is-half">
                <a href="<?php echo URLROOT; ?>/users/confirm" >No confirmation mail?</a>
            </div>
        </div><!-- columns -->
    </form>
    </div><!-- columns -->
</section>

<?php require APPROOT . '/views/common/footer.php'; ?>
