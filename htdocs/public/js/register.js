document.addEventListener('DOMContentLoaded', () => {
  const form        = document.querySelector('form');
  const submit      = document.querySelector('input[type="submit"]');
  const password    = document.getElementById('password');
  const pwdConfirm  = document.getElementById('pwdConfirm');
  const pwdHelper   = document.querySelector('.pwd-helper');
  const pwdHelper2  = document.querySelector('.pwd-helper2');
  const pwdMatch    = document.querySelector('.pwd-match');
  const pwdMatch2   = document.querySelector('.pwd-match2');
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

  submit.addEventListener('click', (event) => {
    event.preventDefault();
    // reset messages after each click
    pwdHelper.hidden = true;
    pwdHelper2.hidden = true;
    pwdMatch.hidden = true;
    pwdMatch2.hidden = true;
    if (!validate_pwd(password) || !validate_pwd(pwdConfirm)) {
      if (!validate_pwd(password))
        pwdHelper.hidden = false;
      if (!validate_pwd(pwdConfirm))
        pwdHelper2.hidden = false;
      return;
    }
    if (password.value === pwdConfirm.value) {
      console.log('all good');
      const submitEvent = new SubmitEvent('submit', { submitter: submit });
      form.dispatchEvent(submitEvent);      // All good
    } else {
      pwdMatch.hidden = false;
      pwdMatch2.hidden = false;
    }
  });
}); // End of 'DOMContentLoaded' event listener/handler

function validate_pwd(field) {
  if (field.value.match(/^[0-9a-z]{6,255}$/))
    return true;
  else
    return false;
}
