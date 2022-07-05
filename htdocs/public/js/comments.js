document.addEventListener('DOMContentLoaded', () => {
  const showComments = document.querySelectorAll('.show-comments');
  const hideComments = document.querySelectorAll('.hide-comments');
  const comments = document.querySelectorAll('.fa-comment');
  // Select the buttons in the card footer to comment
  const commentBtns = document.querySelectorAll('.comment-btn');

  showComments.forEach(link => {
    link.addEventListener('click', (e) => {
      e.target.parentElement.style.display = 'none';
      e.target.parentElement.nextElementSibling.style.display = 'block';
    });
  });

  hideComments.forEach(link => {
    link.addEventListener('click', (e) => {
      e.target.parentElement.parentElement.style.display = 'none';
      e.target.parentElement.parentElement.previousElementSibling.style.display = 'block';
    });
  });

  comments.forEach(comment => {
    comment.addEventListener('click', (e) => {
      // traverse to card footer and display it
      console.log(`comment btn: ${e.target}`);
    });
  });

  commentBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault(); // prevent form submission
      // console.log(`pic id: ${e.target.getAttribute('data-id')} comment: ${comment}`);

      data = new FormData();
      data.append('pic_id', e.target.getAttribute('data-id'));
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
`
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
<hr>`;
        // Traverse from button clicked to the comment form
        commentForm = e.target.parentElement.parentElement.parentElement;
        // Insert new comment (with Ajax data) before the comment form
        commentForm.insertAdjacentHTML('beforebegin', newComment);
        // console.log(comment); // testing
      })
      .catch(error => console.log(`Woops! ${error}`));
    });
  });
  // Add event-listener/handler to footer to write the comment
  // Add fetch request to send the comment to the back-end
});