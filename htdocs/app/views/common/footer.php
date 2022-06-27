  <hr>
  <footer>rodrodri &copy;<?php echo date('Y'); ?></footer>
</div><!-- container -->
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