<?php require APPROOT . '/views/common/header.php'; ?>

<h2><?php echo $title; ?></h2>
<div class="container">
  <main>
    <div class="webcam">
      <!-- Webcam stream -->
      <video id="video" autoplay width="640" height="480"></video>
      <br>
      <button id="snap">Pic it!</button>
    </div>

    <!-- Snapshot will show up here -->
    <div id="preview">
      <!-- canvas for edit/preview snapshot goes here -->
    </div>

    <!-- Form to upload the pic -->
    <form action="<?php echo URLROOT . '/pics/camera'; ?>" id="form">
      <input type="submit" name="upload" value="Upload" id="submit">
    </form>

    <!-- Dinamically load the stickers -->
    <div class="stickers">
      <?php
        $images = glob('assets/stickers/' . "*.png");
        foreach ($images as $img)
          echo '<img src="' . URLROOT . "/$img" . '" alt="">';
      ?>
    </div>
  </main>

  <div class="sidebar">
    <!-- Dinamically load the user's taken pics -->
  </div>
</div><!-- container -->
<?php require APPROOT . '/views/common/footer.php'; ?>
