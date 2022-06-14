<?php require APPROOT . '/views/common/header.php'; ?>

<h2><?php echo $title; ?></h2>

<!-- flash messages -->
<ul>
    <?php if(isset($flashes)) : ?>
        <? foreach ($flashes as $flash) : ?>
            <? echo '<li>' . $flash . '</li>'; ?>
        <? endforeach; ?>
    <?php endif; ?>
    <!-- <?php if(isset($success)) : ?>
        <? echo '<li>' . $success . '</li>'; ?>
    <?php endif; ?> -->
</ul>
<p>(5 <sub><s>dick</s></sub>pics per page)</p>

<?php require APPROOT . '/views/common/footer.php'; ?>
