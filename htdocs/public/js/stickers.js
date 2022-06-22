// Global Array to hold the selected (clicked on) stickers!
var selectedStickers = [];

document.addEventListener('DOMContentLoaded', function () {
  // Select all stickers
  const all = document.querySelectorAll('.sticker');

  // Highlight selected sticker; add them to 'selected' array
  all.forEach((sticker) => {
    sticker.addEventListener('click', (event) => {
      // Do not delete the last sticker (at least one stays).
      if (event.target.style.border && selectedStickers.length > 1) {
        event.target.style.border = '';
        selectedStickers.splice(selectedStickers.indexOf(event.target), 1);
        // Do not add stickers already in the array (not repeated).
      } else if (!selectedStickers.includes(event.target)) {
        event.target.style.border = '2px solid green';
        selectedStickers.push(event.target);
      }
      console.log(selectedStickers);//testing....
    })
  });
}); // End of 'DOMContentLoaded' event listener/handler
