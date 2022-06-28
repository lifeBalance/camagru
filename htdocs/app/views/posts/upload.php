<?php require APPROOT . '/views/common/header.php'; ?>

<h2><?php echo $title; ?></h2>
<div class="container">

  <main>
    <div id="card" hidden>
      <div id="previewDiv" hidden>
        <canvas id="canvas"></canvas>
      </div>

      <form action="<?php echo URLROOT . '/posts/upload'; ?>" id="form">
        <label for="fileInput">Select an image: </label>
        <input type="file" id="fileInput">
        <div id="controls" hidden>
          <label for="comment">Your comment:</label>
          <textarea id="comment" placeholder="Write soming about your image, dawg!"></textarea>
          <input type="submit" name="upload" value="Upload" id="submit">
        </div><!-- controls -->
      </form>
    </div>
    
    <!-- Dinamically load the stickers -->
    <div class="stickers">
      <?php
        $images = glob('assets/stickers/' . "*.png");
        foreach ($images as $img)
        echo '<img src="' . URLROOT . "/$img" . '" class="sticker">';
        ?>
    </div>
  </main>

  <div class="sidebar">
    <!-- Dinamically load the user's taken pics -->
  </div>
</div>

<?php require APPROOT . '/views/common/footer.php'; ?>
