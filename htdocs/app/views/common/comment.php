<div>
    <div class="media mb-1">
        <div class="media-left">
            <figure class="image is-48x48">
                <img src="<?php echo $comment['profile_pic'] ?>" alt="Profile image">
            </figure>
        </div>
        <div class="media-content">
            <p class="title is-6">@<?php echo $comment['author'] ?></p>
            <p class="subtitle is-6"><?php echo $comment['date'] ?></p>
        </div>
    </div>

    <div class="content">
        <p style="word-wrap: break-word;"><?php echo $comment['content'] ?></p>
    </div>
</div>
