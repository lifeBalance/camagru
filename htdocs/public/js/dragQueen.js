document.addEventListener('DOMContentLoaded', () => {
  // DRAGGING STICKERS
  const prevDiv = document.getElementById('previewDiv');
  let   deltaX = 0, deltaY = 0, startX = 0, startY = 0;
  let   active = false;

  // For getting delta on pointer position
  prevDiv.addEventListener('pointerdown',  ptrDownHandler, false);

  function ptrDownHandler(e) {
    e.preventDefault();

    prevDiv.addEventListener('pointermove',  ptrMoveHandler, false);
    prevDiv.addEventListener('pointerup',    ptrUpHandler, false);
    // Get a starting value to calculate the pointer's position delta
    startX = e.clientX;
    startY = e.clientY;

    active = true;
  }

  function ptrMoveHandler(e) {
    if (e.target.parentElement.classList.contains('clone')) {
      // "Box" of the sticker
      let boxRect = e.target.parentElement.getBoundingClientRect();

      // calculate the change in cursor location
      deltaX = startX - e.clientX;
      deltaY = startY - e.clientY;

      // with each move we also want to update the start X and Y
      startX = e.clientX;
      startY = e.clientY;

      // set the element's new position:
      if (active) {
        e.target.parentElement.style.top = `${e.target.parentElement.offsetTop - deltaY}px`;
        e.target.parentElement.style.left = `${e.target.parentElement.offsetLeft - deltaX}px`;
      }

      if (getOffset(prevDiv).left + parseInt(e.target.parentElement.style.left) + e.target.parentElement.offsetWidth >= getOffset(prevDiv).right)
        active = false;
      if (getOffset(prevDiv).top + parseInt(e.target.parentElement.style.top) + boxRect.height >= getOffset(prevDiv).bottom)
        active = false;
      if (e.target.parentElement.offsetTop - deltaY <= 0 || e.target.parentElement.offsetLeft - deltaX <= 0)
        active = false;
    }
  }

  function ptrUpHandler(e) {
    active = false;
    if (e.target.parentElement.classList.contains('clone')) {
      sticker = selectedStickers.find(obj => obj.name == e.target.parentElement.id);
      // Store the sticker's position in the selectedStickers array.
      sticker.xPos = e.target.parentElement.style.left;
      sticker.yPos = e.target.parentElement.style.top;
      // console.log(`${sticker.name}: ${sticker.xPos}:${sticker.yPos}`);
    }
  }

  function getOffset(el) {
    const rect = el.getBoundingClientRect();
    return {
      left:   parseInt(rect.left + window.scrollX),
      right:  parseInt(rect.right + window.scrollX),
      top:    parseInt(rect.top + window.scrollY),
      bottom: parseInt(rect.bottom + window.scrollY),
    };
  }
}); // End of 'DOMContentLoaded' event listener/handler