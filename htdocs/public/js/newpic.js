// Select 'file input' element.
const picFileSel = document.getElementById('picFileSel');

// Add event listener to 'file input' element.
picFileSel.addEventListener("change", function () {
  // Select 'div' element (parent of 'img' preview).
  const picContainer = document.getElementById('picContainer');

  if (picFileSel.files[0]) {
    // Create 'img' element.
    const picPreview = document.createElement('img');
    // Set attributes of the new 'img' element
    picPreview.setAttribute('id', 'picPreview');
    picPreview.setAttribute("style", "width:500px");
    picPreview.setAttribute('src',  window.URL.createObjectURL(picFileSel.files[0]));
    // Append 'img' element to 'div' container.
    picContainer.appendChild(picPreview);
  }
});
