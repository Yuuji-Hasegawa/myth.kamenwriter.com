export const urlCopy = () => {
  let shareBtn = document.querySelector('#shareBtn');
  let shareURL = document.querySelector('#shareURL');
  let target = document.querySelector('.c-input-copy');

  function copyToClip() {
    shareURL.select();
    let text = shareURL.value;
    let messageBox = document.createElement('dialog');
    messageBox.classList.add('c-copy-message');
    return navigator.clipboard
      .writeText(text)
      .then(function () {
        messageBox.innerHTML = 'クリップボードにコピー';
        shareURL.parentNode.appendChild(messageBox);
        setTimeout(() => {
          let dialogBox = document.querySelector('.c-copy-message');
          dialogBox.remove();
          shareBtn.disabled = false;
        }, 4000);
      })
      .catch(function (error) {
        messageBox.innerHTML = 'コピーに失敗しました';
        shareURL.parentNode.appendChild(messageBox);
        setTimeout(() => {
          let dialogBox = document.querySelector('.c-copy-message');
          dialogBox.remove();
          shareBtn.disabled = false;
        }, 4000);
      });
  }
  if (shareBtn != null) {
    shareBtn.addEventListener('click', () => {
      shareBtn.disabled = true;
      copyToClip();
    });
  }
};
