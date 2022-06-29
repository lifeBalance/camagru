  <nav class="navbar is-primary" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
      <a class="navbar-item" href="<?php echo URLROOT; ?>">
        <img src="<?php echo URLROOT . '/assets/logo.png'; ?>" alt="Camagru" height="60"><?php echo SITENAME; ?>
      </a>

      <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </a>
    </div><!-- navbar-brand -->

    <div class="navbar-menu">
      <?php if (isset($_SESSION['username'])) : ?>
        <div class="navbar-start">
          <div class="navbar-item has-dropdown is-hoverable">
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
        </div><!-- navbar start -->

        <div class="navbar-end">
          <div class="navbar-item">
            <div class="buttons">
              <a class="button is-light" href="<?php echo URLROOT . '/login/out'; ?>">
                Log out
              </a>
            </div>
          </div>
        </div><!-- navbar end -->
      <?php else : ?><!-- ELSE -->
        <div class="navbar-end">
          <div class="navbar-item">
            <div class="buttons">
              <a class="button is-primary"  href="<?php echo URLROOT . '/login/new' ?>">
                Log in
              </a>
            </div>
          </div>
        </div><!-- navbar end -->
      <?php endif; ?>
    </div> <!-- navbar menu -->
  </nav>

