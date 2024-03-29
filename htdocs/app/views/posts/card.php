<div class="card block">
    <div class="card-image">
        <figure class="image">
            <img src="<?php echo $post['url_pic']?>" alt="Post image">
        </figure>
    </div>

    <div class="card-content p-0">
        <div class="media mb-1">
            <!-- Like icon -->
            <span class="media-left">
            <?php
                if (isset($_SESSION['user_id'])) {
                    if ($post['user_liked'])
                        echo '<i class="fa-solid fa-heart is-clickable has-text-danger p-2" id="'. $post['pic_id'] .'"></i>';
                    else
                        echo '<i class="fa-regular fa-heart is-clickable p-2" id="'. $post['pic_id'] . '"></i>';
                } else {
                    echo '<i class="fa-solid fa-heart p-2" id="'. $post['pic_id'] . '"></i>';
                }
                echo ' <span>' . $post['likes'] . '</span> likes';
            ?>
            </span>

            <!-- Comment modals opener -->
            <span class="media-right">
            <?php
                // Pluralize the word comment
                if ($post['comments_qty'] == 1)
                    $comment_str = 'comment';
                else
                    $comment_str = 'comments';
                echo '<i class="fa-regular fa-comment is-clickable has-text-primary p-2 open-modal" data-id="open-modal-'. $post['pic_id'] .'"></i>' . '<span id="comments-qty-' . $post['pic_id'] . '">' . $post['comments_qty'] . '</span> ' . $comment_str;
            ?>
            </span>
        </div>
    </div><!-- card-content -->
    <!-- The modal view -->
    <?php require APPROOT . '/views/posts/comments_modal.php'; ?>
</div>