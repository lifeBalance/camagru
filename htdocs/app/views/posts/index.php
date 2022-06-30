<?php require APPROOT . '/views/common/header.php'; ?>

<section class="section">
    <div class="columns is-centered">
        <div class="column is-two-thirds">
            <?php require APPROOT . '/views/common/flashes.php'; ?>
            
            <?php foreach($posts as $post) {
                // echo 'pic id="pic' . $post['pic_id'] . '"<br>';
                // echo $post['url'] . '<br>';
                // echo 'Liked by you? ';
                // if ($post['liked'])
                // echo 'yes<br>';
                // else
                // echo 'no<br>';
                // echo $post['likes'] . ' motherfuckers liked this pic<br>';
                // foreach($post['comments'] as $comment)
                // echo $comment['comment'] . '<br>';
                require APPROOT . '/views/common/card.php';
            }
            ?>
        </div>
    </div>
</section>

<?php require APPROOT . '/views/common/footer.php'; ?>
