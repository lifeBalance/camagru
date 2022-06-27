<?php require APPROOT . '/views/common/header.php'; ?>
    <h2><?php echo $title; ?></h2>
    <main>
      <div id="card" hidden>
        <div id="previewDiv">
          <!-- canvas and video will toggle when button is pressed -->
          <canvas id="canvas" hidden></canvas>
          <video id="video" autoplay width="640" height="480"></video>
        </div><!-- Preview Box -->

        <div id="controls">
          <div>
            <button id="snapBtn">Pic it!</button>
          </div>

          <form id="form" action="<?php echo URLROOT . '/pics/camera'; ?>" hidden>
            <label for="comment">Your comment:</label>
            <textarea id="comment" placeholder="Write soming about your image, dawg!"></textarea>
            <div>
              <input id="submit" type="submit" value="Upload">
            </div>
          </form>
        </div><!-- Controls -->
      </div><!-- Card -->

      <!-- Dinamically load the stickers -->
      <div class="stickers">
        <?php
          $images = glob('assets/stickers/' . "*.png");
          foreach ($images as $idx => $img) {
            echo '<img src="' . URLROOT . "/$img" . '" class="sticker" alt="sticker">';
            if ($idx < array_key_last($images))
              echo "\n\t\t\t\t";
            else
              echo "\n";
          }
        ?>
      </div> <!-- stickers -->
    </main>

<!-- Area for the user's taken pics -->
<!-- <div class="sidebar"> -->
  <!-- Dinamically load the user's taken pics -->
<!-- </div> -->

<?php require APPROOT . '/views/common/footer.php'; ?>
