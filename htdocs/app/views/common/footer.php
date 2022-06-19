    </div>
    <hr>
    <footer>rodrodri &copy;<?php echo date('Y'); ?></footer>
    <?php if(isset($scripts)) : ?>
        <?php foreach($scripts as $script) : ?>
            <?php echo '<script src="' . URLROOT . '/js/' . $script . '"></script>'; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>