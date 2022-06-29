<?php require APPROOT . '/views/common/header.php'; ?>

<section class="section">
    <?php require APPROOT . '/views/common/flashes.php'; ?>
    
    <h2>Gallery</h2>
<?php foreach($posts as $post) {
    echo '<div style="border: 2px solid red">' . "\n";
    echo 'pic id="pic' . $post['pic_id'] . '"<br>';
    echo $post['url'] . '<br>';
    echo 'Liked by you? ';
    if ($post['liked'])
    echo 'yes<br>';
    else
    echo 'no<br>';
    echo $post['likes'] . ' motherfuckers liked this pic<br>';
    foreach($post['comments'] as $comment)
    echo $comment['comment'] . '<br>';
    echo '</div>' . "\n";
}
?>
</section>
<p>(5 <sub><s>dick</s></sub>pics per page)</p>

<?php require APPROOT . '/views/common/footer.php'; ?>
