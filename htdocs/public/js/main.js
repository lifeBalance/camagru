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

  if (messages.length > 0) {
    messages.forEach(msg => {
      // Had to use 'onclick' to get rid of 'undefined' on console 
      msg.onclick = e => {
        e.target.parentElement.parentElement.remove();
      }
    });
  }
});