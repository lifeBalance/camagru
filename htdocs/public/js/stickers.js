// Global Array to hold the selected (clicked on) stickers!
var selectedStickers = [];

document.addEventListener('DOMContentLoaded', function () {
  // Select all stickers
  const all         = document.querySelectorAll('.sticker');
  const previewDiv  = document.getElementById('previewDiv');
  const snapBtn     = document.getElementById('snapBtn') || false;
  const fileInput   = document.getElementById('fileInput') || false;
  const fInputLabel = document.getElementById('fInputLabel') || false;
  let   clickedOnSticker;
  let   tmp;

  // Highlight selected sticker; add them to 'selected' array
  all.forEach((sticker) => {
    sticker.addEventListener('click', (event) => {
      // Unveil image preview and control buttons
      if (fileInput && fInputLabel) {
        fileInput.disabled = false;
        fInputLabel.textContent = 'Choose your file...';
      }

      // Convenience variables to store the clicked on sticker and its 'name'.
      clickedOnSticker = event.target; //  clicked on img (not figure)
      let stickerName = clickedOnSticker.src.split('/').pop().split('.')[0];

      // Do not delete a non-existing sticker (at least one stays).
      if (clickedOnSticker.classList.contains('highlight') &&
          selectedStickers.length >= 0) {
        // Remove the border of the sticker
        clickedOnSticker.classList.remove('highlight');
        // Remove the clone of the sticker
        document.getElementById(stickerName).remove();
        // Remove the sticker from the array
        selectedStickers.splice(selectedStickers.indexOf(selectedStickers.find(obj => obj.name === stickerName)), 1);

        // Do not add stickers already in the array (highlighted).
      } else if (!selectedStickers.find(obj => obj.name === stickerName)) {
        clickedOnSticker.classList.add('highlight');
        // Clone the sticker
        tmp = clickedOnSticker.cloneNode(true);
        tmp.width = clickedOnSticker.width;
        tmp.height = clickedOnSticker.height;
        tmp.classList.replace('highlight', 'clone');  // Class switcharoo
        tmp.setAttribute('id', stickerName);
        // Push the clone information to an array of objects
        selectedStickers.push({
          'name': tmp.id,
          'xPos':   0,
          'yPos':   0,
          'width':  tmp.width,
          'height': tmp.height,
        });
        // Attach the clone to the preview div
        previewDiv.appendChild(tmp);
        // If there's a snapBtn, enable it (stickers were selected)
        if (snapBtn)
          snapBtn.disabled = false;
      }
    })  // End of event-listener
  });
}); // End of 'DOMContentLoaded' event listener/handler
