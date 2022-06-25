document.addEventListener('DOMContentLoaded', function () {
  const previewDiv  = document.getElementById('previewDiv');
  const fileInput   = document.getElementById('fileInput');
  const canvas      = document.getElementById('canvas');
  const context     = canvas.getContext('2d');
  const submit      = document.getElementById('submit');
  const form        = document.getElementById('form');
  let reader        = new FileReader();
  let formData;
  let img           = new Image();

  fileInput.addEventListener('change', function (e) {
    if (fileInput.files[0]) {
      reader.readAsDataURL(fileInput.files[0]);
      reader.onloadend = function () {
        img.src = reader.result;
        img.addEventListener('load', function () {
          canvas.width = img.width;
          canvas.height = img.height;
          context.drawImage(img,0,0); 
          submit.hidden = false;
          previewDiv.hidden = false;
        });
        img.onerror = function () {
          submit.hidden = true;
          window.alert("That's not a picture my man!");
        }
      }
    }
    // context.drawImage(img, 0, 0, img.width, img.height);
  });
  
  submit.addEventListener('click', function (e) {
    e.preventDefault(); // Stop the default event (submitting form)
    
    // Put the data in a FormData object
    formData = new FormData();
    formData.append('img',  canvas.toDataURL());

    // Extract the URL from the value of 'action' in the form
    fetch(form.getAttribute('action'), {
      method: 'POST',
      body: formData,
    })
    .then(response => {
      window.location.assign(response.url);
    })
    .then(data => {
      return data;
    })
    .catch((error) => {
      console.error('Error:', error);
    });
  }); // End of 'submit' event listener/handler
}); // End of 'DOMContentLoaded' event listener/handler
