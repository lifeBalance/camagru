document.addEventListener('DOMContentLoaded', () => {
  const isTouchDevice = () => {  
    return window.matchMedia("(pointer: coarse)").matches  
  }

  // DRAGGING STICKERS
  const prevDiv = document.getElementById('previewDiv');
  let   deltaX = 0, deltaY = 0, startX = 0, startY = 0;
  let   active = false;

  if (isTouchDevice())
  {
    prevDiv.addEventListener('touchstart', touchStartHandler, {passive: true});
  } else {
    prevDiv.addEventListener('pointerdown',  ptrDownHandler, false);
  }

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
    e.preventDefault();

    if (e.target.classList.contains('clone')) {
      // "Box" of the sticker
      let boxRect = e.target.getBoundingClientRect();

      // calculate the change in cursor location
      deltaX = startX - e.clientX;
      deltaY = startY - e.clientY;

      // with each move we also want to update the start X and Y
      startX = e.clientX;
      startY = e.clientY;

      // set the element's new position:
      if (active) {
        e.target.style.top = `${e.target.offsetTop - deltaY}px`;
        e.target.style.left = `${e.target.offsetLeft - deltaX}px`;
      }

      if (getOffset(prevDiv).left + parseInt(e.target.style.left) + e.target.offsetWidth >= getOffset(prevDiv).right)
        active = false;
      if (getOffset(prevDiv).top + parseInt(e.target.style.top) + boxRect.height >= getOffset(prevDiv).bottom)
        active = false;
      if (e.target.offsetTop - deltaY <= 0 || e.target.offsetLeft - deltaX <= 0)
        active = false;
    }
  }

  function ptrUpHandler(e) {
    active = false;
    if (e.target.classList.contains('clone')) {
      sticker = selectedStickers.find(obj => obj.name == e.target.id);
      // Store the sticker's position in the selectedStickers array.
      sticker.xPos = parseInt(e.target.style.left);
      sticker.yPos = parseInt(e.target.style.top);
      // console.log(`ptr: ${sticker.name}: [${sticker.xPos}, ${sticker.yPos}]`);
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

  function touchMoveHandler(e) {
    // e.preventDefault(); // Don't use this here!!

    if (e.target.classList.contains('clone')) {
      // "Box" of the sticker
      let boxRect = e.target.getBoundingClientRect();

      deltaX = startX - e.changedTouches[0].clientX;
      deltaY = startY - e.changedTouches[0].clientY;

      startX = e.changedTouches[0].clientX;
      startY = e.changedTouches[0].clientY;

      // set the element's new position:
      if (active) {
        e.target.style.top = `${e.target.offsetTop - deltaY}px`;
        e.target.style.left = `${e.target.offsetLeft - deltaX}px`;
      }

      if (getOffset(prevDiv).left + parseInt(e.target.style.left) + e.target.offsetWidth >= getOffset(prevDiv).right)
        active = false;
      if (getOffset(prevDiv).top + parseInt(e.target.style.top) + boxRect.height >= getOffset(prevDiv).bottom)
        active = false;
      if (e.target.offsetTop - deltaY <= 0 || e.target.offsetLeft - deltaX <= 0)
        active = false;
    }
  }

  function touchStartHandler(e) {
    // e.preventDefault(); // Don't use this here!!

    prevDiv.addEventListener('touchmove', touchMoveHandler, { passive: true});
    prevDiv.addEventListener('touchend', touchEndHandler, { passive: true} );
    // Get a starting value to calculate the pointer's position delta
    startX = e.touches[0].clientX;
    startY = e.touches[0].clientY;
    active = true;
  }

  function touchEndHandler(e) {
    active = false;
    if (e.target.classList.contains('clone')) {
      sticker = selectedStickers.find(obj => obj.name == e.target.id);
      // Store the sticker's position in the selectedStickers array.
      sticker.xPos = parseInt(e.target.style.left);
      sticker.yPos = parseInt(e.target.style.top);
      // console.log(`touch: ${sticker.name}: [${sticker.xPos}, ${sticker.yPos}]`);
    }
  }

}); // End of 'DOMContentLoaded' event listener/handler