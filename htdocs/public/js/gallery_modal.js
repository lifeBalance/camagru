document.addEventListener('DOMContentLoaded', () => {
  const openGallery = document.querySelector('.open-gallery');
  const closeGallery = document.querySelector('.close-gallery');
  const galleryModal = document.querySelector('.gallery-modal');
  const deleteIcons = document.querySelectorAll('.delete-post') || [];

  // Functions to open and close a modal
  function openModal(el) {
    el.classList.add('is-active');
  }
  function closeModal(el) {
    el.classList.remove('is-active');
  }

  openGallery.addEventListener('click', () => {
    openModal(galleryModal);
  });
  closeGallery.addEventListener('click', () => {
    closeModal(galleryModal);
  });

  // Attaching event-listeners to remove posts
  deleteIcons.forEach(icon => {
    icon.addEventListener('click', (e) => {
      let postId = e.target.id.replace('delete-', '');
      const submodal = document.getElementById(`submodal-${postId}`);
      const cancelBtn = document.getElementById(`cancel-delete-${postId}`);
      const confirmBtn = document.getElementById(`confirm-delete-${postId}`);

      // Open the submodal to either cancel or confirm deletion
      openModal(submodal);
      // Select button to cancel deletion (just close submodal)
      cancelBtn.addEventListener('click', () => {
        closeModal(submodal);
      });

      // select button to confirm deletion (and close submodal)
      confirmBtn.addEventListener('click', () => {
        // console.log('About to delete post: ' + postId);
        const post = document.getElementById(`post-${postId}`);

        // Append to the form the id of the post we want to delete
        data = new FormData();
        data.append('post_id', postId);

        // Interesting way of getting the URL we want
        url = new URL('/posts/delete', window.location.href);
        fetch(url.href, {
          method: 'POST',
          body: data
        })
        .then(response => {
            // return response.text() // for checking back-end errors
            return response.json();
        })
        .then(respJSON => {
          if (respJSON.post_id == postId)
            post.remove(); // remove post from DOM
        })
        .catch(error => console.log(`Woops! ${error}`));
      });
    });
  });
});