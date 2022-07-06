  <footer class="footer has-background-primary-dark">
    <div class="content has-text-centered">
      <p class="has-text-light">
        <strong class="yellow">Camagru</strong> by <a href="https://github.com/lifeBalance" ><strong class="brownie">rodrodri</strong></a> &copy;<?php echo date('Y'); ?>
      </p>
    </div>
  </footer>
  <!-- Source scripts selectively -->
  <?php if(isset($scripts))
    foreach($scripts as $idx => $script) {
      echo '<script src="' . URLROOT . '/js/' . $script . '"></script>';
      if ($idx < array_key_last($scripts))
        echo "\n\t";
      else
        echo "\n";
    }
  ?>
</body>
</html>
