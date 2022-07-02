<div class="card block">
    <div class="card-image">
        <figure class="image is-1by1">
            <img src="<?php echo $post['url_pic']?>" alt="Post image">
        </figure>
    </div>

    <div class="card-content">
        <div class="media mb-1">
            <!-- Like icon section -->
            <span class="media-left">
                <?php
                    if (isset($_SESSION['user_id'])) {
                        if ($post['user_liked'])
                            echo '<i class="fa-solid fa-heart is-clickable has-text-danger" id="'. $post['pic_id'] .'"></i>';
                        else
                            echo '<i class="fa-regular fa-heart is-clickable" id="'. $post['pic_id'] . '"></i>';
                    } else {
                        echo '<i class="fa-solid fa-heart" id="'. $post['pic_id'] . '"></i>';
                    }
                    echo ' <span>' . $post['likes'] . '</span> likes';
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
            $comment = $post['comments'][0];
            echo '<div class="author-comment">';
                require APPROOT . '/views/common/comment.php';
            echo '</div>';
            // Link to show the comments
            echo '<div class="has-text-centered">';
            echo '  <a  class="show-comments">Show comments</a>';
            echo '</div>';

            echo '<div class="other-comments" hidden>';// hide them at the beginning!
            foreach($post['comments'] as $k => $comment) {
                if ($k == 0)
                    continue ;
                else
                    require APPROOT . '/views/common/comment.php';
            }
            // <!-- Link to show the comments -->
            echo '  <div class="has-text-centered">';
            echo '    <a class="hide-comments" >Hide comments</a>';
            echo '  </div>';
            echo '</div>';
            ?>
        </div><!-- comments-section -->
    </div><!-- card-content -->
</div>