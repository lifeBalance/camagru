<div class="media pl-2 pt-2">
    <div class="media-left">
        <figure class="image is-48x48">
            <img src="<?php echo Img::url_profile_pic($_SESSION['user_id']) ?>" alt="Profile picture">
        </figure>
    </div>

    <div class="media-content">
        <p class="title is-6">@<?php echo $_SESSION['username'] ?></p>
        <p class="subtitle is-6 mb-2">Write your comment:</p>
        <div class="field">
            <input type="text" class="input is-medium mb-1" maxlength="255" placeholder="Max. 255 characters"></input>
            <p class="is-size-7"><span class="count">255</span> characters left.</p>
            <button class="button is-primary comment-btn" data-id="btn-<?php echo $post['pic_id'] ?>">Comment</button>
        </div>
    </div>
</div>
