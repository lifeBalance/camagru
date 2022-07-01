<div class="card block">
    <div class="card-image">
        <figure class="image is-4by3">
            <img src="<?php echo $post['url_pic']?>" alt="Post image">
        </figure>
    </div>

    <div class="card-content">
        <div class="media mb-1">
            <!-- Like icon section -->
            <span class="media-left">
                <?php
                    if ($post['liked'])
                        echo '<i class="fa-solid fa-heart has-text-danger"></i>';
                    else
                        echo '<i class="fa-solid fa-heart"></i>';
                    echo ' ' . $post['likes'] . ' likes';
                ?>
            </span>
            <!-- Comment icon section -->
            <span class="media-right">
                <i class="fa-solid fa-comment"></i>
                Comment
            </span>
        </div>
        <div class="card-content comments-section">
            <?php
                foreach($post['comments'] as $k => $comment) {
                    if ($k == 0) {
                        echo '<div class="author-comment">';
                            require APPROOT . '/views/common/comment.php';
                        echo '</div>';
                        echo '<div class="has-text-centered">';
                        echo '  <a  class="show-comments">Show comments</a>';
                        echo '</div>';
                    } else {
                        echo '<div class="other-comments" >';// hide them at the beginning!
                            require APPROOT . '/views/common/comment.php';
                        echo '</div>';
                    }
                }
            ?>
            <div class="has-text-centered">
                <a class="hide-comments" >Hide comments</a>
            </div>
        </div><!-- comments-section -->
    </div><!-- card-content -->
</div>