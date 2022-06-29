  <footer class="footer is-primary">
    <div class="content has-text-centered">
      <p>
        <strong>Camagru</strong> by <a href="https://github.com/lifeBalance">rodrodri</a>. &copy;<?php echo date('Y'); ?>
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
