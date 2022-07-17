document.addEventListener('DOMContentLoaded', () => {
  const password    = document.getElementById('password');
  const pwdConfirm  = document.getElementById('pwdConfirm');
  const pwdHelper   = document.querySelector('.pwd-helper');
  const pwdHelper2  = document.querySelector('.pwd-helper2');
  const pwdMatch2   = document.querySelector('.pwd-match2');
  const gravatar    = document.getElementById('gravatar');
  const gravHelper  = document.querySelector('.grav-helper');
  const username    = document.getElementById('username');
  const counter     = document.getElementById('counter');
  const span        = counter.firstElementChild;

  username.addEventListener('keyup', (e) => {
    span.textContent = 50 - username.value.length;
    if ((50 - username.value.length) === 0) {
      username.classList.add('is-danger');
    } else {
      username.classList.remove('is-danger');
    }
  });

  password.addEventListener('input', (e) => {
    pwdHelper.hidden = true;  // hide it if there's not pattern mismatch!

    if (password.validity.patternMismatch) {
      pwdHelper.hidden = false;
      return;
    }
  });

  pwdConfirm.addEventListener('input', (e) => {
    pwdHelper2.hidden = true;  // hide it at the beginning
    pwdMatch2.hidden = true;  // hide it at the beginning

    if (pwdConfirm.validity.patternMismatch) {
      pwdHelper2.hidden = false;
      return;
    }
    if (pwdConfirm.value !== password.value) {
      pwdMatch2.hidden = false;
      pwdConfirm.classList.add('is-danger');
      return;
    } else {
      pwdMatch2.hidden = true;
      pwdConfirm.classList.remove('is-danger');
      return;
    }
  });

  gravatar.addEventListener('input', (e) => {
    gravHelper.hidden = true;  // hide it if there's no mismatch!

    if (gravatar.validity.typeMismatch) {
      gravHelper.hidden = false;
      return;
    }
  });
}); // End of 'DOMContentLoaded' event listener/handler
