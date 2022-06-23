<?php require APPROOT . '/views/common/header.php'; ?>

<h2><?php echo $title; ?></h2>
<div class="container">
  <main>
    <div id="card" hidden>
      <div id="previewBox">
        <!-- canvas and video will toggle when button is pressed -->
        <canvas id="canvas" hidden></canvas>
        <video id="video" autoplay width="640" height="480"></video>
      </div><!-- Preview Box -->

      <div id="controls">
        <button id="snapBtn">Pic it!</button>
        <br>
        <!-- Form to upload the pic -->
        <form action="<?php echo URLROOT . '/pics/camera'; ?>" id="form">
          <input type="submit" name="upload" value="Upload" id="submit">
        </form>
      </div><!-- Controls -->
    </div><!-- Card -->

    <!-- Dinamically load the stickers -->
    <div class="stickers">
      <?php
        $images = glob('assets/stickers/' . "*.png");
        foreach ($images as $img)
          echo '<img src="' . URLROOT . "/$img" . '" class="sticker">';
      ?>
    </div>
  </main>

  <!-- Area for the user's taken pics -->
  <div class="sidebar">
    <!-- Dinamically load the user's taken pics -->
  </div>
</div><!-- container -->
<?php require APPROOT . '/views/common/footer.php'; ?>
