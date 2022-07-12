<div class="modal" data-id="modal-<?php echo $post['pic_id']?>">
    <div class="modal-background"></div>
    <div class="modal-card">
    <header class="modal-card-head p-2">
        <p class="modal-card-title"><i class="fa-solid fa-comment-dollar pl-2 pr-2"></i> Comments</p>
        <button class="delete" aria-label="close" data-id="<?php echo 'close-modal-' . $post['pic_id']?>"></button>
    </header>
    <section class="modal-card-body" data-id="section-<?php echo $post['pic_id']?>">
        <?php
            foreach($post['comments'] as $k => $comment) {
                require APPROOT . '/views/common/comment.php';
                if ($k == 0)
                    echo '<hr class="mt-1 op-hr" >';
                else if ($k < count($post['comments']) - 1)
                    echo '<hr class="mt-1">';
            }
        ?>
    </section>
    <footer class="modal-card-foot p-1">
        <?php
        // Button to submit the comment
        if (isset($_SESSION['user_id'])) {
            require APPROOT . '/views/common/comment_form.php';
        }
        ?>
    </footer>
    </div>
</div>