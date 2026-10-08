(function () {
  var REFERENCE_ARROW = 50;
  var ARROW_RING_PAD = 250;

  var PAIRS = [
    ['prev-arrow', 'band-arrow-prev'],
    ['next-arrow', 'band-arrow-next']
  ];

  function source(cls) {
    var el = document.querySelector('.' + cls);
    if (!el) return null;
    return el.querySelector('img') || el;
  }

  function syncArrowBands() {
    PAIRS.forEach(function (pair) {
      var band = document.querySelector('.' + pair[1]);
      if (!band) return;

      var el = source(pair[0]);
      var rect = el && el.getBoundingClientRect();

      if (!rect || !rect.width || !isVisible(el)) {
        band.style.display = 'none';
        return;
      }

      var boost = parseFloat(getComputedStyle(band).getPropertyValue('--ring-boost')) || 1;
      var ring = (rect.width / REFERENCE_ARROW) * boost;
      var size = (Math.max(rect.width, rect.height) + ARROW_RING_PAD * 2) * ring;

      band.style.display = '';
      band.style.setProperty('--ring', ring.toFixed(3));
      band.style.width = size + 'px';
      band.style.height = size + 'px';
      band.style.left = (rect.left + rect.width / 2 - size / 2) + 'px';
      band.style.top = (rect.top + rect.height / 2 - size / 2) + 'px';
    });
  }

  function isVisible(el) {
    return !!(el.offsetWidth || el.offsetHeight || el.getClientRects().length);
  }

  function watch() {
    PAIRS.forEach(function (pair) {
      var el = document.querySelector('.' + pair[0]);
      if (!el) return;

      el.addEventListener('mouseenter', syncArrowBands);
      el.addEventListener('touchstart', syncArrowBands, { passive: true });

      if (window.ResizeObserver) {
        var observer = new ResizeObserver(syncArrowBands);
        observer.observe(el);
        if (el.parentElement) observer.observe(el.parentElement);
      }
    });
  }

  function start() {
    syncArrowBands();
    watch();
  }

  window.addEventListener('resize', syncArrowBands);
  window.addEventListener('scroll', syncArrowBands, { passive: true });
  window.addEventListener('load', syncArrowBands);

  if (document.readyState === 'complete') start();
  else document.addEventListener('DOMContentLoaded', start);

  document.addEventListener('touchstart', function () {}, { passive: true });
})();
