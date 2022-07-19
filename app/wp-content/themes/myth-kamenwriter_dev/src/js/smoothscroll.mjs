import smoothscroll from 'smoothscroll-polyfill';
smoothscroll.polyfill();

export const smoothScroll = () => {
  let elems = document.querySelectorAll('a[href^="#"]');
  if (elems) {
    elems.forEach((elem) => {
      let href = elem.getAttribute('href').split('#')[1];
      if (href) {
        let target = document.getElementById(href);
        if (target) {
          elem.addEventListener('click', (e) => {
            e.preventDefault();
            target.scrollIntoView({
              behavior: 'smooth',
              block: 'start',
            });
          });
        }
      }
    });
  }
};