document.addEventListener('DOMContentLoaded', () => {
  const openModals = document.querySelectorAll('.open-modal');
  const closeButtons = document.querySelectorAll('.delete') || false;
  const formBtns = document.querySelectorAll('.comment-btn');

  // Functions to open and close a modal
  function openModal($el) {
    $el.classList.add('is-active');
  }
  function closeModal($el) {
    $el.classList.remove('is-active');
  }

  // Attaching event-listeners to OPEN the comment modals
  openModals.forEach(link => {
    link.addEventListener('click', (e) => {
      // Find the modal using its class and data-id
      linkDataId = e.target.getAttribute('data-id');
      modalDataId = linkDataId.replace('open-modal-', 'modal-');
      const modal = document.querySelector(`[data-id=${modalDataId}]`);
      openModal(modal);
    });
  });

  // Attaching event-listeners to CLOSE the comment modals
  closeButtons.forEach(button => {
    // Flash messages also have '.delete' class
    if (button.hasAttribute('data-id'))
    {
      button.addEventListener('click', (e) => {
        postId = e.target.getAttribute('data-id').replace('close-modal-', '');
        const modal = document.querySelector(`[data-id=modal-${postId}]`);
        closeModal(modal);
      });
    }
  });
  
  // Attaching event-listeners to fetch the form to the server
  formBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault(); // prevent form submission

      btnDataId = e.target.getAttribute('data-id');
      
      sectionDataId = linkDataId.replace('open-modal-', 'section-');

      data = new FormData();
      data.append('pic_id', e.target.getAttribute('data-id').replace('btn-', ''));
      // Traverse up to the text area to get the comment
      let comment = e.target.previousElementSibling.value;
      data.append('comment', comment);

      url = window.location.href + 'posts/comment';
      fetch(url, {
        method: 'POST',
        body: data
      })
      .then(response => {
          // return response.text() // for checking back-end errors
          return response.json();
        })
      .then(comment => {
        // console.log(comment) // for checking back-end errors
        newComment =
`<hr>
<div>
  <div class="media mb-1">
    <div class="media-left">
      <figure class="image is-48x48">
        <img src="${comment.profile_pic}" alt="Profile image">
      </figure>
    </div>
    <div class="media-content">
        <p class="title is-6">@${comment.author}</p>
        <p class="subtitle is-6">${comment.ago}</p>
    </div>
  </div>

  <div class="content">${comment.comment}</div>
</div>`;
        // Select section with the comments
        commentsSection = document.querySelector(`[data-id=${sectionDataId}]`);
        // Insert new comment (with Ajax data) at the end of the secion
        commentsSection.insertAdjacentHTML('beforeend', newComment);
        // Clear the text input
        e.target.previousElementSibling.value = '';
        // console.log(comment); // Testing answer from server
      })
      .catch(error => console.log(`Woops! ${error}`));
    });
  });

});