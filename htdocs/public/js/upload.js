document.addEventListener('DOMContentLoaded', function () {
  const previewDiv  = document.getElementById('previewDiv');
  const previewImg  = document.getElementById('previewImg');
  const fileInput   = document.getElementById('fileInput');
  const controls    = document.getElementById('controls');
  const form        = document.getElementById('form');
  const submit      = document.getElementById('submit');
  const canvas      = document.getElementById('canvas');
  const comment     = document.getElementById('comment');
  const context     = canvas.getContext('2d');
  let   formData;
  let   file;
  // Character counter (comment text area)
  const counter     = document.getElementById('counter');

  fileInput.onchange = (e) => {
    if (e.target.files[0]) {
      file = fileInput.files[0];

      if (file.type.match('image.*')) {
        previewImg.src = window.URL.createObjectURL(file);
        previewDiv.style.display = 'block';  // show img and sticker
        controls.style.display = 'block';    // show upload button
        // Draw the img onto the hidden canvas for fetching it
        previewImg.onload = () => {
          canvas.width = previewImg.clientWidth;
          canvas.height = previewImg.clientHeight;
          context.drawImage(previewImg, 0, 0, previewImg.clientWidth, previewImg.clientHeight);
        }
      }
    }
  }

  submit.addEventListener('click', function (e) {
    e.preventDefault(); // Stop the default event (submitting form)

    // Put the data in a FormData object
    formData = new FormData();
    formData.append('img', canvas.toDataURL());

    // Check that the user wrote some comment
    if (comment.value == '') {
      comment.classList.add('is-danger');
      return ;
    } else {
      formData.append('comment', comment.value);
    }

    // Append the stickers intel to the form data (if there's any)
    if (selectedStickers.length > 0) {
      let stickers = '[';
      for (let i = 0; i < selectedStickers.length; i++) {
        const st = selectedStickers[i];
        stickers += `["${st.name}", "${st.xPos}", "${st.yPos}", "${st.width}", ${st.height}]`;
        if (i < selectedStickers.length - 1)
          stickers += ', ';
      }
      stickers += ']';
      formData.append('stickers', stickers);
    }

    // Extract the URL from the value of 'action' in the form
    fetch(form.getAttribute('action'), {
      method: 'POST',
      body: formData,
    })
    .then(response => {
      const urlObject = new URL(response.url);
      window.location.assign(urlObject.origin);
    })
    .catch((error) => {
      console.error('Error:', error);
    });
  }); // End of 'submit' event listener/handler

  // Add character counter to comment form
  comment.addEventListener('keyup', (e) => {
    counter.textContent = 255 - comment.value.length;
    if ((255 - comment.value.length) === 0) {
      comment.classList.add('is-danger');
    } else {
      comment.classList.remove('is-danger');
    }
  });
}); // End of 'DOMContentLoaded' event listener/handler
