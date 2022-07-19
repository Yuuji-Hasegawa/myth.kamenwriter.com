export const redume = () => {
  let redumeBtn = document.querySelector('#redumeBtn');
  let redumeBody = document.querySelector('#redumeBody');
  let redumeMarker = document.querySelector('.c-redume-marker');

  function slideDown() {
    redumeBtn.classList.add('o-has-icon:redume-is-open');
    redumeBody.classList.add('c-list:redume-is-open');
    redumeMarker.classList.add('c-redume-marker:is-open');
  }
  function slideUp() {
    redumeBtn.classList.remove('o-has-icon:redume-is-open');
    redumeBody.classList.remove('c-list:redume-is-open');
    redumeMarker.classList.remove('c-redume-marker:is-open');
  }
  if (redumeBtn != null) {
    redumeBtn.addEventListener('click', () => {
      if (!redumeBtn.classList.contains('o-has-icon:redume-is-open')) {
        slideDown();
      } else {
        slideUp();
      }
    });
  }
};