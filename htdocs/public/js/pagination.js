document.addEventListener('DOMContentLoaded', () => {
  const prev  = document.querySelector('.pagination-previous');
  const next  = document.querySelector('.pagination-next');
  const page  = document.querySelector('.pagination').getAttribute('data-page');
  const pages = document.querySelector('.pagination').getAttribute('data-pages');

  // Tag links with 'is-disabled' class if we're at first/last page
  if (parseInt(page) == 1)
  {
    prev.classList.add('is-disabled');
    // console.log(`prev: ${page} out of ${pages}`);
  }
  if (parseInt(page) >= parseInt(pages))
  {
    next.classList.add('is-disabled');
    // console.log(`next: ${page} out of ${pages}`);
  }

  // Event listener/handler for the previous page button
  prev.addEventListener('click', function (e) {
    if (prev.classList.contains('is-disabled'))
    {
      e.preventDefault();
      e.stopPropagation();
      prev.removeAttribute("href");
      return false;
    }
  });
  
  // Event listener/handler for the next page button
  next.addEventListener('click', function (e) {
    if (next.classList.contains('is-disabled'))
    {
      e.preventDefault();
      e.stopPropagation();
      next.removeAttribute("href");
      return false;
    }
    // console.log(`${page} out of ${pages}`);
  });
}); // End of 'DOMContentLoaded' event listener/handler
