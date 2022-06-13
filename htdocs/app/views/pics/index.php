<?php require APPROOT . '/views/common/header.php'; ?>

<h2><?php echo $title; ?></h2>

<!-- flash messages -->
<ul>
    <?php if(isset($errors)) : ?>
        <? foreach ($errors as $k => $v) : ?>
            <? echo '<li>' . $v . '</li>'; ?>
        <? endforeach; ?>
    <?php endif; ?>
    <?php if(isset($success)) : ?>
        <? echo '<li>' . $success . '</li>'; ?>
    <?php endif; ?>
</ul>
<p>(5 <sub><s>dick</s></sub>pics per page)</p>

<?php require APPROOT . '/views/common/footer.php'; ?>
