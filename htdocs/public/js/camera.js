document.addEventListener('DOMContentLoaded', () => {
  // Select 'div' element (parent of 'canvas' preview).
  const preview = document.getElementById('preview');
  const video   = document.getElementById('video');
  const canvas  = document.createElement('canvas');
  const snap    = document.getElementById('snap');
  const form    = document.getElementById('form');
  const submit  = document.getElementById('submit');
  const context = canvas.getContext('2d');
  let   imageData;
  let   formData;

  // Event listener/handler for the button to take the pic
  snap.addEventListener('click', function () {
    canvas.width = video.width;
    canvas.height = video.height;
    context.drawImage(video, 0, 0, video.width, video.height);
    preview.appendChild(canvas);
  });

  // Event listener/handler for the button to upload the pic
  submit.addEventListener('click', function (e) {
    // Stop the default event (submitting form) when clicking on 'upload'
    e.preventDefault();

    // Put the data of the canvas on a variable
    imageData = canvas.toDataURL();

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

  // Let's get the party started!
  startStream();
}); // End of 'DOMContentLoaded' event listener/handler

async function startStream() {
  try {
    const stream = await navigator.mediaDevices.getUserMedia({ video: true });
    handleStream(stream);
  } catch (error) {
    console.log(error.toString());
  }
}

function handleStream(s) {
  video.srcObject = s;
  video.play();
}
