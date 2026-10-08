(function () {
  var MIN_FIT = 0.4;
  var STEP = 0.02;

  var box = document.querySelector('.current-title-box');
  if (!box) return;

  function fits() {
    return box.scrollHeight <= box.clientHeight;
  }

  function fit() {
    box.style.setProperty('--title-fit', '1');
    if (fits()) return;

    for (var scale = 1 - STEP; scale >= MIN_FIT; scale -= STEP) {
      box.style.setProperty('--title-fit', scale.toFixed(3));
      if (fits()) return;
    }

    box.style.setProperty('--title-fit', MIN_FIT.toFixed(3));
  }

  var queued = false;

  function schedule() {
    if (queued) return;
    queued = true;
    requestAnimationFrame(function () {
      queued = false;
      fit();
    });
  }

  if (window.MutationObserver) {
    new MutationObserver(schedule).observe(box, {
      childList: true,
      characterData: true,
      subtree: true
    });
  }

  window.addEventListener('resize', schedule);
  window.addEventListener('titlebox:resize', schedule);
  window.addEventListener('load', schedule);

  if (document.fonts && document.fonts.ready) document.fonts.ready.then(schedule);

  schedule();
})();
