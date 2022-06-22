<?php require APPROOT . '/views/common/header.php'; ?>

<h2><?php echo $title; ?></h2>
<div class="container">

  <main>
    <div id="previewBox">
      <!-- image for edit/preview will be inserted here -->
    </div>

    <form action="<?php echo URLROOT . '/pics/upload'; ?>" method="post" enctype="multipart/form-data" id="form">
      <input type="file" name="dickpic" id="fileInput">
      <br>
      <input type="submit" name="upload" value="Upload">
    </form>

    <!-- Dinamically load the stickers -->
    <div class="stickers">
      <?php
        $images = glob('assets/stickers/' . "*.png");
        foreach ($images as $img)
        echo '<img src="' . URLROOT . "/$img" . '" class="sticker">';
        ?>
    </div>
  </main>

  <div class="sidebar">
    <!-- Dinamically load the user's taken pics -->
  </div>
</div>

<?php require APPROOT . '/views/common/footer.php'; ?>
