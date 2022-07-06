<div class="card mb-3" id="post-<?php echo $pic['id']?>">
    <figure class="image">
        <img src="<?php echo URLROOT . '/uploads/' . $pic['filename'] . '.png' ?>" alt="image">
    </figure>
    <div class="card-content p-1">
        <p class="icon-text p-2 ml-2">
            <span class="icon has-text-danger">
                <i class="fa-solid fa-trash-can delete-post is-clickable" id="delete-<?php echo $pic['id']?>"></i>
            </span>
            <span> Delete Post</span>
        </p>
    </div>

    <!-- Nested modal for confirmation -->
    <div id="submodal-<?php echo $pic['id'] ?>" class="modal confirmation">
        <div class="modal-background">
        </div>

        <div class="modal-card">
            <section class="modal-card-body">
                <p>Are you sure you wanna delete this Post?</p>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-danger" id="confirm-delete-<?php echo $pic['id'] ?>" aria-label="close">Delete</button>
                <button class="button" aria-label="close" id="cancel-delete-<?php echo $pic['id']?>">Cancel</button>
            </footer>
        </div>
    </div><!-- submodal -->
</div>
