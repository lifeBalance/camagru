<?php require APPROOT . '/views/common/header.php'; ?>

<section class="section">
  <div class="columns is-centered">
    <div class="column is-two-thirds">
      <?php require APPROOT . '/views/common/flashes.php'; ?>

      <h2 class="title"><?php echo $title; ?></h2>

      <main>
        <div id="card" hidden >
          <div id="previewDiv" >
            <!-- canvas and video will toggle when button is pressed -->
            <canvas id="canvas" hidden width="640" height="480"></canvas>
            <video id="video" autoplay width="640" height="480"></video>
          </div><!-- Preview Box -->

          <div id="controls">
            <div>
              <button id="snapBtn" class="button is-primary">Pic it!</button>
            </div>

            <form id="form" action="<?php echo URLROOT . '/posts/camera'; ?>" hidden>
              <div class="field mt-5">
                <label for="comment" class="label">Your comment:</label>
                <textarea id="comment" placeholder="Write soming about your image, dawg!" class="textarea"></textarea>
              </div>
              <div class="field">
                <input id="submit" type="submit" value="Upload" class="control button is-primary">
              </div>
            </form>
          </div><!-- Controls -->
        </div><!-- Card -->

        <!-- Dinamically load the stickers -->
        <hr>
        <h2 class="title mb-3">Select some sticker</h2>
        <div class="stickers mt-5 box is-clearfix">
          <?php
            $images = glob('assets/stickers/' . "*.png");
            foreach ($images as $idx => $img) {
              $url = URLROOT . "/$img";
              require APPROOT . '/views/common/sticker.php';
            }
          ?>
        </div> <!-- stickers -->
      </main>
    </div> <!-- column -->
  </div> <!-- columns -->
</section>

<!-- Area for the user's taken pics -->
<!-- <div class="sidebar"> -->
  <!-- Dinamically load the user's taken pics -->
<!-- </div> -->

<?php require APPROOT . '/views/common/footer.php'; ?>
