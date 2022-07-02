document.addEventListener('DOMContentLoaded', () => {

  const hearts = document.querySelectorAll('.fa-heart');

  hearts.forEach(heart => {
    heart.addEventListener('click', (e) => {
      console.log(e.target.id)
      console.log(window.location.href+ 'posts/like')
      url = window.location.href + 'posts/like';
      data = new FormData();
      data.append('pic_id', e.target.id);
      fetch(url, {
        method: 'POST',
        body: data,
      })
      .then(response => response.json())
      .then(liked => {
        if (liked.liked === 'yep') {
          e.target.classList.add('has-text-danger');
          e.target.nextElementSibling.textContent = 
          parseInt(e.target.nextElementSibling.textContent) + 1;
        } else {
          e.target.classList.remove('has-text-danger');
          e.target.nextElementSibling.textContent =
          parseInt(e.target.nextElementSibling.textContent) - 1;
        }
        console.log(liked.liked);
      })
      .catch(error => console.log(`Woops! ${error}`));
    });
  });
});