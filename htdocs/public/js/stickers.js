// Global Array to hold the selected (clicked on) stickers!
var selectedStickers = [];

document.addEventListener('DOMContentLoaded', function () {
  // Select all stickers
  const all         = document.querySelectorAll('.sticker');
  const card        = document.getElementById('card');
  const previewDiv  = document.getElementById('previewDiv');
  let   tmp;
  let   name;
  let   clickedOnSticker;

  // Highlight selected sticker; add them to 'selected' array
  all.forEach((sticker) => {
    sticker.addEventListener('click', (event) => {
      // Unveil image preview and control buttons
      card.hidden = false;

      // Convenience variables to store the clicked on sticker and its 'name'.
      clickedOnSticker = event.target;
      name = clickedOnSticker.src.split('/').pop().split('.')[0];

      // Do not delete a non-existing sticker (at least one stays).
      if (clickedOnSticker.classList.contains('highlight') &&
          selectedStickers.length >= 0) {
        // Remove the border of the sticker
        clickedOnSticker.classList.remove('highlight');
        // Remove the clone of the sticker
        document.getElementById(name).remove();
        // Remove the sticker from the array
        selectedStickers.splice(selectedStickers.indexOf(selectedStickers.find(obj => obj.name === name)), 1);

        // Do not add stickers already in the array (not repeated).
      } else if (!selectedStickers.find(obj => obj.name === name)) {
        clickedOnSticker.classList.add('highlight');
        tmp = clickedOnSticker.cloneNode(true);
        tmp.classList.replace('highlight', 'clone');
        tmp.setAttribute('id', name);
        selectedStickers.push({
          'name': name,
          'xPos':   0,
          'yPos':   0,
        });
        previewDiv.appendChild(tmp);
      }
    })  // End of event-listener
  });
}); // End of 'DOMContentLoaded' event listener/handler
