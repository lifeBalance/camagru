  <nav>
    <h1><a href="<?php echo URLROOT;?>"><?php echo SITENAME; ?></a></h1>
    <ul>
    <?php if (isset($_SESSION['username'])) : ?>
      <li>
        <p><?php echo '<a href="'. URLROOT .'/pics/upload">'; ?>Upload pic</a></p>
      </li>
      <li>
        <p><?php echo '<a href="'. URLROOT .'/pics/camera">'; ?>Take a snapshot</a></p>
      </li>
      <li>
        <p><?php echo 'Welcome <a href="'. URLROOT .'/users/settings">' . $_SESSION['username'] . '!</a> | <a href="' . URLROOT . '/login/out">Log out</a>'; ?></p>
      </li>
    <?php else : ?>
      <li>
        <a href="<?php echo URLROOT; ?>/login/new">Login</a>
      </li>
    <?php endif; ?>
    </ul>
  </nav>