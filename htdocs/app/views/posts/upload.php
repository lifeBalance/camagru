<?php require APPROOT . '/views/common/header.php'; ?>

<section class="section">
  <div class="columns is-centered">
    <div class="column is-two-thirds">
      <?php require APPROOT . '/views/common/flashes.php'; ?>

      <h2 class="title"><?php echo $title; ?></h2>

      <main>
        <!-- <div id="card" class="hide"> -->
          <!-- Div contains img file and sticker (shows up when file is loaded -->
          <div id="previewDiv" class="hide">
            <canvas id="canvas" class="hide"></canvas>
            <img src="" alt="" id="previewImg">
          </div>
          <!-- File input (shows up when sticker is selected -->
          <div class="field is-grouped">
            <?php require  APPROOT . '/views/common/upload.php' ?>
            <input id="gallery" type="submit" value="Gallery" class="control button is-link ml-2 open-gallery">
          </div>

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
        <!-- </div> -->

        <hr>
        <!-- Stickers -->
        <h2 class="title mb-3">Select some sticker</h2>
        <?php require APPROOT . '/views/common/stickers_grid.php'; ?>
      </main>
    </div><!-- column -->
  </div><!-- columns -->

  <!-- Gallery modal (Fuck sidebars!) -->
  <?php require APPROOT . '/views/posts/gallery_modal.php' ?>
</section>

<?php require APPROOT . '/views/common/footer.php'; ?>
