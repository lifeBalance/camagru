<?php require APPROOT . '/views/common/header.php'; ?>

<section class="section">
  <div class="columns is-centered">
    <div class="column is-two-thirds">
      <?php require APPROOT . '/views/common/flashes.php'; ?>

      <h2 class="title"><?php echo $title; ?></h2>

      <main>
        <div id="card" hidden>
          <div id="previewDiv" hidden>
            <canvas class="column is-full" id="canvas"></canvas>
          </div>

          <form action="<?php echo URLROOT . '/posts/upload'; ?>" id="form">
            <?php require  APPROOT . '/views/common/upload.php' ?>
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
        
        <!-- Dinamically load the stickers -->
        <div class="stickers">
          <hr>
          <h2 class="title">Select some sticker</h2>
          <?php
            $images = glob('assets/stickers/' . "*.png");
            foreach ($images as $img)
            echo '<img src="' . URLROOT . "/$img" . '" class="sticker">';
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
