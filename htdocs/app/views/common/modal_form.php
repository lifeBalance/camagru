<div class="media pl-2 pt-2">
    <div class="media-left">
        <figure class="image is-48x48">
            <img src="<?php echo $comment['profile_pic'] ?>" alt="Profile picture">
        </figure>
    </div>

    <div class="media-content">
        <p class="title is-6">@<?php echo $comment['author'] ?></p>
        <p class="subtitle is-6 mb-2">Write your comment:</p>
        <div class="field">
            <input type="text" class="input is-medium mb-1" placeholder="Write a comment"></input>
            <button class="button is-primary comment-btn" data-id="btn-<?php echo $post['pic_id'] ?>">Comment</button>
        </div>
    </div>
</div>
