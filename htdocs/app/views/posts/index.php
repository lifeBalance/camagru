<?php require APPROOT . '/views/common/header.php'; ?>

<section class="section">
    <div class="columns is-centered">
        <div class="column is-two-thirds">
            <?php require APPROOT . '/views/common/flashes.php'; ?>

            <?php foreach($posts as $post) {
                require APPROOT . '/views/common/card.php';
            }
            ?>
        </div>
    </div>
</section>

<?php require APPROOT . '/views/common/footer.php'; ?>
