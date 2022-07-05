<div class="media">
    <div class="media-left">
        <figure class="image is-48x48">
            <img src="<?php echo $comment['profile_pic'] ?>" alt="Profile picture">
        </figure>
    </div>
    <div class="media-content">
        <form>
            <textarea class="textarea is-small mb-1" placeholder="Write a comment"></textarea>
            <button class="button is-primary comment-btn" data-id="<?php echo $post['pic_id'] ?>">Comment</button>
        </form>
    </div>
</div>
