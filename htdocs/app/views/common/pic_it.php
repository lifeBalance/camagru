<div class="navbar-item is-hidden-mobile is-hidden-tablet-only dropdown is-hoverable">
    <div class="dropdown-trigger">
        <button class="button is-link" aria-haspopup="true" aria-controls="dropdown-menu">
            <span><i class="fa-solid fa-camera-retro"></i> New Post</span>
            <span class="icon is-small">
            <i class="fas fa-angle-down" aria-hidden="true"></i>
            </span>
        </button>
    </div>
    <div class="dropdown-menu" id="dropdown-menu" role="menu">
        <div class="dropdown-content">
            <a href="<?php echo URLROOT . '/posts/camera'; ?>" class="dropdown-item">
            Webcam
            </a>
            <a href="<?php echo URLROOT . '/posts/upload'; ?>" class="dropdown-item">
            Upload
            </a>
        </div>
    </div>
</div>