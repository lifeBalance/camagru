<?php require APPROOT . '/views/common/header.php'; ?>

<section class="section">
  <div class="columns is-centered">
    <div class="column is-two-thirds">
      <?php require APPROOT . '/views/common/flashes.php'; ?>

      <h2 class="title"><?php echo $title; ?></h2>

      <main>
        <div id="card">
          <div id="previewDiv">
            <!-- canvas is always hidden -->
            <canvas id="canvas" hidden ></canvas>
            <video id="video" autoplay ></video>
          </div><!-- Preview Box -->

          <div id="controls">
            <div>
              <button id="snapBtn" class="button is-primary" disabled>Pic it!</button>
              <input id="gallery" type="submit" value="Gallery" class="control button is-link open-gallery">
            </div>

            <form id="form" action="<?php echo URLROOT . '/posts/camera'; ?>" hidden>
              <div class="field mt-5">
                <label for="comment" class="label">Your comment:</label>
                <textarea id="comment" placeholder="Write soming about your image, dawg!" class="textarea" maxlength="255" placeholder="Max. 255 characters"></textarea>
                <p class="is-size-7"><span class="count" id="counter">255</span> characters left.</p>
              </div>
              <div class="field">
                <input id="submit" type="submit" value="Upload" class="control button is-primary">
              </div>
            </form>
          </div><!-- Controls -->
        </div><!-- Card -->

        <hr>
        <!-- Stickers -->
        <h2 class="title mb-3">Select some sticker</h2>
        <?php require APPROOT . '/views/common/stickers_grid.php'; ?>
      </main>
    </div> <!-- column -->
  </div> <!-- columns -->

  <!-- Gallery modal (Fuck sidebars!) -->
  <?php require APPROOT . '/views/posts/gallery_modal.php' ?>
</section>

<?php require APPROOT . '/views/common/footer.php'; ?>
