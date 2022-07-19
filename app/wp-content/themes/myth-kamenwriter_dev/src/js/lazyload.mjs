export const lazyLoad = () => {
  let img = document.querySelectorAll('img[data-src]');
  const io = new IntersectionObserver(inViewport, {
    threshold: [0],
  });
  Array.from(img).forEach((element) => {
    io.observe(element);
  });
  function inViewport(entries, observer) {
    entries.forEach((entry) => {
      if (entry.intersectionRatio > 0) {
        const imgEl = entry.target;
        imgEl.src = imgEl.dataset.src;

        if (imgEl.dataset.srcset) {
          imgEl.srcset = imgEl.dataset.srcset;
        }

        if (imgEl.parentNode) {
          const srcs = imgEl.parentNode.querySelectorAll('source[data-srcset]');
          srcs.forEach((src) => {
            src.srcset = src.dataset.srcset;
          });
        }

        imgEl.addEventListener('load', () => {
          imgEl.removeAttribute('data-src');
          if (imgEl.dataset.srcset) {
            imgEl.removeAttribute('data-srcset');
          }
          if (imgEl.parentNode) {
            const srcs = imgEl.parentNode.querySelectorAll('source[data-srcset]');
            srcs.forEach((src) => {
              src.removeAttribute('data-srcset');
            });
          }
        });
        io.unobserve(entry.target);
      }
    });
  }
};
