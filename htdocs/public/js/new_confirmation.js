document.addEventListener('DOMContentLoaded', () => {
  const password    = document.querySelector('input[type="password"]');
  const pwdHelper   = document.querySelector('.pwd-helper');

  password.addEventListener('input', (e) => {
    pwdHelper.hidden = true;  // hide it if there's no pattern mismatch!

    if (password.validity.patternMismatch) {
      pwdHelper.hidden = false;
      return;
    }
  });
}); // End of 'DOMContentLoaded' event listener/handler

