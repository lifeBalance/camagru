document.addEventListener('DOMContentLoaded', function () {
  const previewBox  = document.getElementById('previewBox');
  const fileInput   = document.getElementById('fileInput');
  const submit      = document.getElementById('submit');
  const form        = document.getElementById('form');
  let   picPreview;

  // Add event listener/handler to 'file input' element.
  fileInput.addEventListener('change', function (e) {
    if (!picPreview) {
      picPreview = document.createElement('img');
      // Set attributes of the new 'img' element
      picPreview.setAttribute('id', 'picPreview');
      picPreview.setAttribute("style", "width:500px"); // Move to CSS
      picPreview.setAttribute('src',  window.URL.createObjectURL(fileInput.files[0]));
      previewBox.appendChild(picPreview);
      previewBox.hidden = false;
    } else {
      picPreview.setAttribute('src',  window.URL.createObjectURL(fileInput.files[0]));
    }
  });

  // Event listener/handler for the button to upload the pic
  submit.addEventListener('click', function (e) {
    // Stop the default event (submitting form) when clicking on 'upload'
    e.preventDefault();

    // Put the data in a FormData object
    formData = new FormData();
    formData.append('img', imageData);

    // Extract the URL from the value of 'action' in the form
    fetch(form.getAttribute('action'), {
      method: 'POST',
      body: formData,
    })
    .then(response => response.text())
    .then(data => {
      return data;
    })
    .catch((error) => {
      console.error('Error:', error);
    });
  }); // End of 'submit' event listener/handler
}); // End of 'DOMContentLoaded' event listener/handler