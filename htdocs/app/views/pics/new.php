<?php require APPROOT . '/views/common/header.php'; ?>

<h2><?php echo $title; ?></h2>
<form action="<?php echo URLROOT . '/pics/upload'; ?>" method="post" enctype="multipart/form-data">
    <div id="picContainer">
        <!-- img preview will be inserted here -->
        <img src="" alt="" id="picPreview">
    </div>

    <div>
        <input type="file" name="dickpic" id="picFileSel">
    </div>

    <div>
        <input type="submit" name="upload" value="Upload">
    </div>
</form>

<?php require APPROOT . '/views/common/footer.php'; ?>
