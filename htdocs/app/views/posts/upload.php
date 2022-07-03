<?php require APPROOT . '/views/common/header.php'; ?>

<section class="section">
  <div class="columns is-centered">
    <div class="column is-two-thirds">
      <?php require APPROOT . '/views/common/flashes.php'; ?>

      <h2 class="title"><?php echo $title; ?></h2>

      <main>
        <div id="card" hidden>
          <!-- Div contains img file and sticker (shows up when file is loaded -->
          <div id="previewDiv" hidden>
            <canvas id="canvas" class="hide"></canvas>
            <!-- <figure class="image is-square"> -->
              <img src="" alt="" id="previewImg">
              <!-- </figure> -->
          </div>
          <!-- File input (shows up when sticker is selected -->
          <?php require  APPROOT . '/views/common/upload.php' ?>

          <form action="<?php echo URLROOT . '/posts/upload'; ?>" id="form">
            <div id="controls" hidden>
              <div class="field">
                <label for="comment" class="label">Your comment:</label>
                <textarea name="comment" id="comment" placeholder="Write soming about your image, dawg!" class="textarea is-primary"></textarea>
              </div>

              <div class="field">
                <input type="submit" name="upload" value="Upload" id="submit" class="control button is-primary">
              </div>
            </div><!-- controls -->
          </form>
        </div>

        <!-- Stickers -->
        <hr>
        <h2 class="title mb-3">Select some sticker</h2>
        <div class="stickers box is-clearfix mt-5">
          <!-- Dinamically load the stickers -->
          <?php
            $images = glob('assets/stickers/' . "*.png");
            foreach ($images as $idx => $img) {
              $url = URLROOT . "/$img";
              require APPROOT . '/views/common/sticker.php';
            }
            ?>
        </div>
      </main>
    </div><!-- column -->
  </div><!-- columns -->

  <div class="sidebar">
    <!-- Dinamically load the user's taken pics -->
  </div>
</section>

<?php require APPROOT . '/views/common/footer.php'; ?>
