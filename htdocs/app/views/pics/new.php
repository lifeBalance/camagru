<?php require APPROOT . '/views/common/header.php'; ?>

<h2><?php echo $title; ?></h2>
<form action="<?php echo URLROOT . '/pics/upload'; ?>" method="post" enctype="multipart/form-data">
  <div id="preview">
    <!-- canvas for edit/preview will be inserted here -->
  </div>

  <div>
    <input type="file" name="dickpic" id="fileInput">
    <br>
    <input type="submit" name="upload" value="Upload">
  </div>
</form>

<?php require APPROOT . '/views/common/footer.php'; ?>
