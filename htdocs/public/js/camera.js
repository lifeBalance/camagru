document.addEventListener('DOMContentLoaded', () => {
  const video       = document.getElementById('video');
  const previewDiv  = document.getElementById('previewDiv');
  const snapBtn     = document.getElementById('snapBtn');
  const form        = document.getElementById('form');
  const submit      = document.getElementById('submit');
  const canvas      = document.getElementById('canvas');
  const comment     = document.getElementById('comment');
  const context     = canvas.getContext('2d');
  let   formData;
  let   initialized = false;
  // Character counter (comment text area)
  const counter     = document.getElementById('counter');
  // const span        = counter.firstElementChild;

  video.addEventListener(
    'canplay',
    () => {
        if (!initialized) {
            let w = previewDiv.offsetWidth;
            let h = previewDiv.offsetHeight;

            video.setAttribute('width', w);
            video.setAttribute('height', h);
            canvas.setAttribute('width', w);
            canvas.setAttribute('height', h);
            initialized = true
        }
    },
    false
)

  // Event listener/handler for the button to take the pic
  snapBtn.addEventListener('click', function () {
    form.hidden = false;
    // Toggle video and canvas once pics are taken
    if (video.paused) {
      video.play();
      snapBtn.textContent = 'Pic it!'
    } else {
      video.pause();
      context.drawImage(video, 0, 0, canvas.width, canvas.height);
      snapBtn.textContent = 'No likey?'
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
      comment.classList.add('is-danger');
      return ;
    } else {
      formData.append('comment', comment.value);
    }

    // Append the stickers intel to the form data
    if (selectedStickers.length > 0) {
      let stickers = '[';
      for (let i = 0; i < selectedStickers.length; i++) {
        let st = selectedStickers[i];
        stickers += `["${st.name}", "${st.xPos}", "${st.yPos}", "${st.width}", "${st.height}"]`;
        if (i < selectedStickers.length - 1)
          stickers += ', ';
        }
        stickers += ']';
        formData.append('stickers', stickers);
        // console.log(stickers);
        // return;
    }

    // Extract the URL from the value of 'action' in the form
    fetch(form.getAttribute('action'), {
      method: 'POST',
      body: formData,
    })
    .then(response => {
      const urlObject = new URL(response.url);
      window.location.assign(urlObject.origin);
      // return response.json();  // test everything is aight
    })
    .then(parsed => {
      // console.log(parsed); // test everything is aight
    })
    .catch((error) => {
      console.log('Error:', error);
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
