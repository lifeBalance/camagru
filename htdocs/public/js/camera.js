document.addEventListener('DOMContentLoaded', () => {
  const video       = document.getElementById('video');
  const snapBtn     = document.getElementById('snapBtn');
  const form        = document.getElementById('form');
  const submit      = document.getElementById('submit');
  const canvas      = document.getElementById('canvas');
  const comment     = document.getElementById('comment');
  const context     = canvas.getContext('2d');
  let   formData;

  // Set measurements of the canvas
  canvas.width = video.width;
  canvas.height = video.height;

  // Event listener/handler for the button to take the pic
  snapBtn.addEventListener('click', function () {
    form.hidden = false;
    // Toggle video and canvas once pics are taken
    if (canvas.hidden) {
      context.drawImage(video, 0, 0, video.width, video.height);
      video.hidden = true;
      canvas.hidden = false;
      snapBtn.textContent = 'No likey?'
    } else {
      canvas.hidden = true;
      video.hidden = false;
      snapBtn.textContent = 'Pic it!'
    }
  });

  // Event listener/handler for the button to upload the pic
  submit.addEventListener('click', function (e) {
    e.preventDefault(); // Stop the default event (submitting form)

    // Append the snapshot to the form data
    formData = new FormData();
    formData.append('img', canvas.toDataURL());

    // Check that the user wrote some comment
    if (comment.value == '') {
      window.alert('Gotta write some comment, son!');
      return ;
    } else {
      formData.append('comment', comment.value);
    }

    // Append the stickers intel to the form data
    if (selectedStickers.length > 0) {
      let stickers = '[';
      for (let i = 0; i < selectedStickers.length; i++) {
        const st = selectedStickers[i];
        stickers += `["${st.name}", "${st.xPos}", "${st.yPos}"]`;
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
