document.addEventListener('DOMContentLoaded', () => {

  const showComents = document.querySelectorAll('.show-comments');
  const hideComents = document.querySelectorAll('.hide-comments');

  showComents.forEach(link => {
    link.addEventListener('click', (e) => {
      e.target.parentElement.style.display = 'none';
      e.target.parentElement.nextElementSibling.style.display = 'block';
    });
  });

  hideComents.forEach(link => {
    link.addEventListener('click', (e) => {
      e.target.parentElement.parentElement.style.display = 'none';
      e.target.parentElement.parentElement.previousElementSibling.style.display = 'block';
    });
  });
});