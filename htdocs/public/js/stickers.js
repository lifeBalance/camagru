// Global Array to hold the selected (clicked on) stickers!
var selectedStickers = [];

document.addEventListener('DOMContentLoaded', function () {
  // Select all stickers
  const all         = document.querySelectorAll('.sticker');
  const card        = document.getElementById('card');
  const previewDiv  = document.getElementById('previewDiv');
  let   tmp;
  let   name;
  let   toBeRemoved;
  let   clickedOnSticker;

  // Highlight selected sticker; add them to 'selected' array
  all.forEach((sticker) => {
    sticker.addEventListener('click', (event) => {
      // Unveil image preview and control buttons
      card.hidden = false;

      // Convenience variables to store the clicked on sticker and its 'name'.
      clickedOnSticker = event.target;
      name = clickedOnSticker.src.split('/').pop().split('.')[0];

      // Do not delete the last sticker (at least one stays).
      if (clickedOnSticker.classList.contains('highlight') &&
          selectedStickers.length > 1) {
        // Remove the border of the sticker
        clickedOnSticker.classList.remove('highlight');

        // Remove the sticker and its clone
        toBeRemoved = selectedStickers.find(obj => obj.name === name);
        toBeRemoved.clone.remove();
        selectedStickers.splice(selectedStickers.indexOf(toBeRemoved), 1);

        // Do not add stickers already in the array (not repeated).
      } else if (!selectedStickers.find(obj => obj.name === name)) {
        tmp = {
          name:   name,
          orig:   clickedOnSticker,
          clone:  clickedOnSticker.cloneNode(true),
          xPos:   0,
          yPos:   0,
        };
        clickedOnSticker.classList.add('highlight');
        tmp.name = name;
        tmp.clone.classList.add('clone');
        tmp.clone.setAttribute('id', tmp.name);
        selectedStickers.push(tmp);
        previewDiv.appendChild(tmp.clone);
      }
    })  // End of event-listener
  });
}); // End of 'DOMContentLoaded' event listener/handler
