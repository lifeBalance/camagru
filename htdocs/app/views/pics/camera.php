<?php require APPROOT . '/views/common/header.php'; ?>

<h2><?php echo $title; ?></h2>
<div class="container">
  <main>
    <div id="previewBox">
      <!-- canvas and video will toggle when button is pressed -->
      <canvas id="canvas" hidden></canvas>
      <video id="video" autoplay width="640" height="480"></video>
      <br>
      <button id="snapBtn">Pic it!</button>
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

  <!-- Area for the user's taken pics -->
  <div class="sidebar">
    <!-- Dinamically load the user's taken pics -->
  </div>
</div><!-- container -->
<?php require APPROOT . '/views/common/footer.php'; ?>
