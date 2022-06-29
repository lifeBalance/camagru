document.addEventListener('DOMContentLoaded', () => {

  // Get all "navbar-burger" elements
  const navbarBurger = document.querySelector('.navbar-burger');
  const navbarMenu = document.querySelector('.navbar-menu');
  const messages = document.querySelectorAll('.message .delete');

  // Add a click event on the navbarBurger element
  navbarBurger.addEventListener('click', () => {
      // Toggle the "is-active" class on the "navbar-menu"
      navbarMenu.classList.toggle('is-active');
  });

  messages.forEach(msg => {
    msg.addEventListener('click', e => {
      console.log(e.target.parentElement.parentElement.remove());
    });
  });
});