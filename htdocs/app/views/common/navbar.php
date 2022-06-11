<nav>
  <h1><a href="<?php echo URLROOT;?>"><?php echo SITENAME; ?></a></h1>
  <ul>
  <?php if (isset($_SESSION['user_id'])) : ?>
    <li>
      <a><?php echo 'Welcome ' . $_SESSION['name']; ?></a>
    </li>
    <li>
      <a href="<?php echo URLROOT; ?>/users/logout">Log out</a>
    </li>
  <?php else : ?>
    <li>
      <a href="<?php echo URLROOT; ?>/users/register">Register</a>
    </li>
    <li>
      <a href="<?php echo URLROOT; ?>/users/login">Login</a>
    </li>
  <?php endif; ?>
  </ul>
</nav>