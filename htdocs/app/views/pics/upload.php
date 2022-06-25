<?php require APPROOT . '/views/common/header.php'; ?>

<h2><?php echo $title; ?></h2>
<div class="container">

  <main>
    <div id="card" hidden>
      <div id="previewDiv" hidden>
        <canvas id="canvas"></canvas>
      </div>

      <form action="<?php echo URLROOT . '/pics/upload'; ?>" id="form">
        <input type="file" name="dickpic" id="fileInput">
        <br>
        <input type="submit" name="upload" value="Upload" id="submit" hidden>
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
