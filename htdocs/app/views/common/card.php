<div class="card block">
    <div class="card-image">
        <figure class="image is-4by3">
            <img src="<?php echo $post['url_pic']?>" alt="Post image">
        </figure>
    </div>

    <div class="card-content">
        <div class="media mb-1">
            <div class="media-left">
                <figure class="image is-48x48">
                    <img src="<?php echo $post['url_prof_pic'] ?>" alt="Profile image">
                </figure>
            </div>
            <div class="media-content">
                <p class="title is-4">John Smith</p>
                <p class="subtitle is-6">@<?php echo $post['author_nick'] ?></p>
            </div>
        </div>

        <div class="content" id="author-comment">
            <div class="media mb-1">
                <span class="media-left">
                    <i class="fa-solid fa-heart has-text-danger"></i>
                    <?php echo $post['likes'] ?> likes
                </span>
                <span class="media-right">
                    <i class="fa-solid fa-comment"></i>
                    Comment
                </span>
            </div>
            <!-- Comment section -->
            <div class="comments-section">
                <!-- Author's comment -->
                <bold class="has-text-weight-bold">
                    <?php echo $post['author_nick'] ?>
                </bold> <?php echo Time::ago($post['comments'][0]['date']) ?>
                <br>
                <?php echo $post['comments'][0]['content'] ?>
                <!-- Hide 'Show comments' when they're being shown. -->
                <div class="has-text-centered">
                    <hr>
                    <a  class="show-comments">Show comments</a>
                </div>
                <!-- All comments (hidden by default) -->
                <div class="content all-comments" hidden>
                    <?php
                    if (count($post['comments']) > 0) {
                        foreach($post['comments'] as $k => $comment) {
                            if ($k == 0)
                                continue;
                            require APPROOT . '/views/common/comment.php';
                        }
                    }
                    ?>
                    <div class="has-text-centered">
                        <hr>
                        <a  class="hide-comments" >Hide comments</a>
                    </div>
                </div><!-- all comments -->
            </div>
        </div>
    </div>
</div>