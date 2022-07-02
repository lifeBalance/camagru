document.addEventListener('DOMContentLoaded', () => {

  const hearts = document.querySelectorAll('.fa-heart');

  hearts.forEach(heart => {
    heart.addEventListener('click', (e) => {
      url = window.location.href + 'posts/like';
      data = new FormData();
      data.append('pic_id', e.target.id);
      fetch(url, {
        method: 'POST',
        body: data,
      })
      .then(response => response.json())
      .then(liked => {
        if (liked.liked == true) {
          e.target.classList.add('has-text-danger');
          e.target.classList.replace('fa-regular', 'fa-solid');
          e.target.nextElementSibling.textContent = 
          parseInt(e.target.nextElementSibling.textContent) + 1;
        } else {
          e.target.classList.remove('has-text-danger');
          e.target.classList.replace('fa-solid', 'fa-regular');
          e.target.nextElementSibling.textContent =
          Math.max(parseInt(e.target.nextElementSibling.textContent) - 1, 0);
        }
      })
      .catch(error => console.log(`Woops! ${error}`));
    });
  });
});