document.addEventListener('DOMContentLoaded', () => {
  const openModals    = document.querySelectorAll('.open-modal');
  const closeButtons  = document.querySelectorAll('.delete') || false;
  const formBtns      = document.querySelectorAll('.comment-btn');
  const commentBoxes  = document.querySelectorAll('input[type="text"]');

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

      // Get post id from the data-id attribute of the clicked icon
      let postId = e.target.getAttribute('data-id').replace('btn-', '');

      data = new FormData();
      data.append('pic_id', postId);
      // Traverse up to the text input to get the comment
      let comment = e.target.previousElementSibling.previousElementSibling.value;
      if (comment.length == 0) {
        alert('No empty comments son!');
        return ;
      }
      data.append('comment', comment);
      fetch('/posts/comment', {
        method: 'POST',
        body: data
      })
      .then(response => {
          return response.json();
        })
      .then(comment => {
        // console.log(comment) // for checking back-end errors
        const commentsQty = document.getElementById(`comments-qty-${postId}`);
        let amountOfComments = parseInt(commentsQty.textContent);
        let newComment = '';
        // Put a horizontal rule only if there's more than one comment
        if (amountOfComments > 1) {
          newComment = '<hr>';
        }
        newComment +=
`<div>
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

  <div class="content">
    <p style="word-wrap: break-word;">
      ${comment.comment}
    </p>
  </div>
</div>`;
        // Select section with the comments
        commentsSection = document.querySelector(`[data-id=section-${postId}]`);
        // Insert new comment (with Ajax data) at the end of the secion
        commentsSection.insertAdjacentHTML('beforeend', newComment);
        // Clear the text input
        const box = e.target.previousElementSibling.previousElementSibling;
        box.value = '';
        // Reset the counter
        const counter = box.nextElementSibling;
        const span    = counter.firstElementChild;
        span.textContent = 255 - box.value.length;
        // Remove the danger on input (just in case)
        box.classList.remove('is-danger');
        // Increase the comments counter
        commentsQty.textContent = amountOfComments + 1;
        // console.log(comment); // Testing answer from server
      })
      .catch((error) => {
        console.error(`Woops! ${error}`);
      });
    });
  });

  commentBoxes.forEach(box => {
    box.addEventListener('keyup', (e) => {
      const counter = box.nextElementSibling;
      const span    = counter.firstElementChild;

      span.textContent = 255 - box.value.length;
      if ((255 - box.value.length) === 0) {
        box.classList.add('is-danger');
      } else {
        box.classList.remove('is-danger');
      }
    });
  });
});