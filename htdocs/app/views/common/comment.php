<hr>
<bold class="has-text-weight-bold">
    <?php echo $comment['author'] ?>
</bold><br><?php echo Time::ago($comment['date']) ?>
<br>
<?php echo $comment['content'] ?>
