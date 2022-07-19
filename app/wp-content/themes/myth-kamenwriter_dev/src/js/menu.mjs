export const menu = () => {
  let btns = document.querySelectorAll('.c-menu-btn');
  let btn = Array.prototype.slice.call(btns, 0);
  let cover = document.querySelector('.c-sidebar-bg');
  let sidebar = document.querySelector('.c-sidebar');

  function open() {
    for (var i = 0; i < btns.length; i++) {
      btns[i].setAttribute('aria-expanded', 'true');
      btns[i].setAttribute('aria-label', 'menu close');
    }
    let nowscroll = document.documentElement.scrollTop || document.body.scrollTop;
    document.body.style.top = -1 * nowscroll + 'px';
    sidebar.setAttribute('aria-hidden', 'false');
    sidebar.classList.add('c-sidebar:is-open');
    document.body.classList.add('is-fixed');
  }
  function close() {
    var pos = parseInt(document.body.style.top);
    if (pos != '0') {
      pos = pos * -1;
    }
    document.body.style.top = 0;
    for (var i = 0; i < btns.length; i++) {
      btns[i].setAttribute('aria-expanded', 'false');
      btns[i].setAttribute('aria-label', 'menu open');
    }
    sidebar.setAttribute('aria-hidden', 'true');
    sidebar.classList.remove('c-sidebar:is-open');
    document.body.classList.remove('is-fixed');
    window.scrollTo(0, pos);
  }
  btn.forEach((target) => {
    target.addEventListener('click', () => {
      if (target.getAttribute('aria-expanded') == 'false') {
        open();
      } else {
        close();
      }
    });
  });
  cover.addEventListener('click', () => {
    close();
  });
};