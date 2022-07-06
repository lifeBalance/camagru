<div class="modal gallery-modal">
    <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head p-2">
                <p class="modal-card-title"><i class="fa-solid fa-images pl-2 pr-2"></i> Gallery</p>
                <button class="delete close-gallery" aria-label="close"></button>
            </header>

            <section class="modal-card-body" data-id="modal-gallery-<?php echo $_SESSION['user_id']?>">
                <?php
                    foreach($user_pics as $pic) {
                        require APPROOT . '/views/posts/gallery_card.php';
                    }
                ?>
            </section>
        </div>
    </div>
</div>
