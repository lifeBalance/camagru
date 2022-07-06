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

        post.remove(); // remove post from DOM
        // Post removal function goes here
        // call funtion that uses fetch to remove the post (pic, comments, likes.)
        // use response to update the DOM (remove the node)
      });
    });
  });
});