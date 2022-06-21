<?php require APPROOT . '/views/common/header.php'; ?>

<h2><?php echo $title; ?></h2>
<!-- Video will show up here -->

<video id="video" autoplay width="640" height="480"></video>
<br>

<button id="snap">Pic it!</button>
<br>

<!-- Snapshot -->
<canvas id="canvas" width="640" height="480"></canvas>
<br />

<!-- To upload the pic -->
<form action="<?php echo URLROOT . '/pics/camera'; ?>" id="form">
        <input type="submit" name="upload" value="Upload" id="submit">
</form>

<?php require APPROOT . '/views/common/footer.php'; ?>
