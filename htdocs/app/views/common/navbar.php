  <nav class="navbar is-fixed-top is-primary" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
      <a class="navbar-item" href="<?php echo URLROOT; ?>">
        <img src="<?php echo URLROOT . '/assets/logo.png'; ?>" alt="Camagru" height="60" class="mt-2 mr-2 ml-2">
        <h2 class="title is-2 has-text-light"><?php echo SITENAME; ?></h2>
      </a>

      <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false">
        <figure class="image is-64x64 pr-5 pt-2" aria-hidden="true">
          <img src="<?php echo URLROOT . '/assets/burger_icon.png' ?>">
        </figure>
        <!-- <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span> -->
      </a>
    </div><!-- navbar-brand -->

    <div class="navbar-menu">
      <?php if (isset($_SESSION['username'])) : ?>
        <div class="navbar-start">
          <!-- Show dropdown only when burger icon is off -->
          <div class="navbar-item has-dropdown is-hoverable is-hidden-mobile is-hidden-tablet-only">
            <a class="navbar-link">
              Pic it boi!
            </a>

            <div class="navbar-dropdown">
              <a class="navbar-item" href="<?php echo URLROOT . '/posts/camera'; ?>">
                Webcam
              </a>
              <a class="navbar-item" href="<?php echo URLROOT . '/posts/upload'; ?>">
                Upload
              </a>
            </div>
          </div><!-- dropdown -->
          <!-- Show words 'Webcam' and 'Upload' when burger icon is on -->
          <a class="navbar-item is-hidden-desktop" href="<?php echo URLROOT . '/posts/camera'; ?>" >Webcam</a>
          <a class="navbar-item is-hidden-desktop" href="<?php echo URLROOT . '/posts/upload'; ?>" >Upload</a>
        </div><!-- navbar start -->

        <div class="navbar-end">
          <!-- Show profile pic only when burger icon is off -->
          <a class="navbar-item is-hidden-mobile is-hidden-tablet-only" href="<?php echo URLROOT . '/users/settings' ?>"><span class="mr-3"><?php echo $_SESSION['username'] ?></span>
            <figure class="image">
              <img src="<?php echo Img::url_profile_pic($_SESSION['user_id'])?>">
            </figure>
          </a>
          <!-- Show word 'Settings' when burger icon is on -->
          <a class="navbar-item is-hidden-desktop" href="<?php echo URLROOT . '/users/settings' ?>">User Settings</a>

          <div class="navbar-item is-hidden-mobile is-hidden-tablet-only">
            <!-- Show 'Log out' button only when burger icon is off -->
            <div class="buttons">
              <a class="button is-light" href="<?php echo URLROOT . '/login/out'; ?>">
                Log out
              </a>
            </div>
          </div>
          <!-- Show word 'Settings' when burger icon is on -->
          <a class="navbar-item is-hidden-desktop" href="<?php echo URLROOT . '/login/out'; ?>">
            Log out
          </a>
        </div><!-- navbar end -->
      <?php else : ?><!-- ELSE -->
        <div class="navbar-start">
          <a class="navbar-item is-hidden-desktop"  href="<?php echo URLROOT . '/login/new' ?>">
            Log in
          </a>
          <a class="navbar-item is-hidden-desktop"  href="<?php echo URLROOT . '/users/register' ?>">
            Sign Up
          </a>
        </div>
        <div class="navbar-end">
          <div class="navbar-item">
            <div class="buttons">
              <!-- Show 'Log in' button only if you're not at the 'Log in' page, duh! -->
              <?php if ($_SERVER['REQUEST_URI'] != '/login/new') : ?>
                <a class="navbar-item button is-light is-hidden-mobile is-hidden-tablet-only"  href="<?php echo URLROOT . '/login/new' ?>">
                  Log in
                </a>
              <?php endif ?>
              <!-- Show 'Sign Up' button only if you're not at the 'Sign Up' page, duh! -->
              <?php if ($_SERVER['REQUEST_URI'] != '/users/register') : ?>
                <a class="navbar-item button is-link is-hidden-mobile is-hidden-tablet-only"  href="<?php echo URLROOT . '/users/register' ?>">
                  Sign Up
                </a>
              <?php endif ?>
            </div>
          </div>
        </div><!-- navbar end -->
      <?php endif; ?>
    </div> <!-- navbar menu -->
  </nav>

