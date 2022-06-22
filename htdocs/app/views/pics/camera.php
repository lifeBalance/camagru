<?php require APPROOT . '/views/common/header.php'; ?>

<h2><?php echo $title; ?></h2>
<div class="container">
  <main>
    <div class="webcam">
      <!-- Webcam stream will show up here -->
      <video id="video" autoplay width="640" height="480"></video>
      <br>
      <button id="snap">Pic it!</button>
    </div>

    <!-- Snapshot -->
    <div id="preview">
      <!-- canvas for edit/preview snapshot goes here -->
    </div>

    <div>
      <!-- Form to upload the pic -->
      <form action="<?php echo URLROOT . '/pics/camera'; ?>" id="form">
          <input type="submit" name="upload" value="Upload" id="submit">
      </form>
    </div>
  </main>

  <div class="sidebar">
    <!-- Dinamically load the stickers -->
    <?php
      $images = glob('assets/stickers/' . "*.png");
      foreach ($images as $img)
        echo '<img src="' . URLROOT . "/$img" . '" alt=""><br>';
    ?>
  </div>
</div><!-- container -->
<?php require APPROOT . '/views/common/footer.php'; ?>
