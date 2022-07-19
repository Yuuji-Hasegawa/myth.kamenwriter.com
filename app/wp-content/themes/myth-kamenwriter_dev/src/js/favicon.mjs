export const favicon = () => {
  let mql = window.matchMedia('(prefers-color-scheme: dark)');
  let favicon = mql.matches ? path + '/favicon-light.ico' : path + '/favicon.ico';
  function setFavicon(elem) {
    let headMeta = document.head.children;
    for (let i = 0; i < headMeta.length; i++) {
      let prop = headMeta[i].getAttribute('rel');
      if (prop === 'icon') {
        let icon = headMeta[i];
        icon.setAttribute('href', elem);
      }
    }
  }
  setFavicon(favicon);
  mql.addEventListener('change', (e) => {
    let favicon = mql.matches ? path + '/favicon-light.ico' : path + '/favicon.ico';
    setFavicon(favicon);
  });
};