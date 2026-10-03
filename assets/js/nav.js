(function () {
  var SEP = '  ';
  var DOT = '•';
  var STAR = '⍟';

  function makeStar() {
    var s = document.createElement('span');
    s.className = 'navstar';
    s.setAttribute('aria-hidden', 'true');
    s.textContent = STAR;
    return s;
  }

  function makeFill() {
    var f = document.createElement('span');
    f.className = 'navfill';
    f.setAttribute('aria-hidden', 'true');
    return f;
  }

  function measureText(host, text) {
    var probe = document.createElement('span');
    probe.style.position = 'absolute';
    probe.style.visibility = 'hidden';
    probe.style.whiteSpace = 'pre';
    probe.textContent = text;
    host.appendChild(probe);
    var width = probe.getBoundingClientRect().width;
    host.removeChild(probe);
    return width;
  }

  function setup(nav) {
    var navtext = nav.querySelector('.ribbonnav-text');
    if (!navtext) return null;

    var items = [].slice.call(navtext.querySelectorAll('.navitem'));
    if (!items.length) return null;
    items.forEach(function (el) { el.parentNode.removeChild(el); });

    function measure() {
      var probe = document.createElement('div');
      probe.style.cssText =
        'position:absolute;visibility:hidden;white-space:nowrap;left:-9999px;top:0;';
      navtext.appendChild(probe);
      var widths = items.map(function (el) {
        var clone = el.cloneNode(true);
        probe.appendChild(clone);
        return clone.getBoundingClientRect().width;
      });
      var star = makeStar();
      probe.appendChild(star);
      var cs = window.getComputedStyle(star);
      var starTotal =
        star.getBoundingClientRect().width +
        parseFloat(cs.marginLeft || 0) +
        parseFloat(cs.marginRight || 0);
      navtext.removeChild(probe);
      return { widths: widths, starTotal: starTotal };
    }

    function computeLines(containerW, widths, starTotal) {
      var lines = [];
      var cur = [];
      var sum = 0;
      for (var i = 0; i < widths.length; i++) {
        var trySum = sum + widths[i];
        var need = trySum + cur.length * starTotal;
        if (cur.length && need > containerW) {
          lines.push(cur);
          cur = [i];
          sum = widths[i];
        } else {
          cur.push(i);
          sum = trySum;
        }
      }
      if (cur.length) lines.push(cur);
      return lines;
    }

    function build(lines) {
      items.forEach(function (el) {
        if (el.parentNode) el.parentNode.removeChild(el);
      });
      navtext.textContent = '';
      lines.forEach(function (idxs) {
        var line = document.createElement('div');
        line.className = 'navline';
        if (idxs.length === 1) {
          line.appendChild(makeStar());
          line.appendChild(makeFill());
          line.appendChild(items[idxs[0]]);
          line.appendChild(makeFill());
          line.appendChild(makeStar());
        } else {
          idxs.forEach(function (idx, j) {
            if (j > 0) {
              line.appendChild(makeFill());
              line.appendChild(makeStar());
              line.appendChild(makeFill());
            }
            line.appendChild(items[idx]);
          });
        }
        navtext.appendChild(line);
      });
    }

    function fillDots() {
      var fills = [].slice.call(navtext.querySelectorAll('.navfill'));
      if (!fills.length) return;
      fills.forEach(function (el) { el.textContent = ''; });
      var sepW = measureText(fills[0], SEP);
      var dotW = measureText(fills[0], SEP + DOT) - sepW;
      if (dotW <= 0) return;
      fills.forEach(function (el) {
        var available = el.getBoundingClientRect().width;
        var count = Math.floor((available - sepW) / (sepW + dotW));
        if (count <= 0) return;
        var dots = [];
        for (var i = 0; i < count; i++) dots.push(DOT);
        el.textContent = SEP + dots.join(SEP) + SEP;
      });
    }

    function syncRibbon() {
      var ribbon = nav.querySelector('.navribbon');
      var h = (ribbon || nav).getBoundingClientRect().height;
      if (!h) return;
      var left = nav.querySelector('.ribbon-left .ribbon-outline');
      var right = nav.querySelector('.ribbon-right .ribbon-outline');
      if (left) {
        left.setAttribute('points',
          '12,0 0,0 12,' + (h / 2) + ' 0,' + h + ' 12,' + h);
      }
      if (right) {
        right.setAttribute('points',
          '0,0 12,0 0,' + (h / 2) + ' 12,' + h + ' 0,' + h);
      }
    }

    return function layout() {
      var containerW = navtext.getBoundingClientRect().width;
      if (!containerW) return;
      var m = measure();
      build(computeLines(containerW, m.widths, m.starTotal));
      fillDots();
      syncRibbon();
    };
  }

  function init() {
    var layouts = [].slice
      .call(document.querySelectorAll('.ribbonnav'))
      .map(setup)
      .filter(Boolean);

    if (!layouts.length) return;

    function layoutAll() {
      layouts.forEach(function (fn) { fn(); });
    }

    window.addEventListener('resize', layoutAll);
    window.addEventListener('load', layoutAll);
    layoutAll();
    if (document.fonts && document.fonts.ready) document.fonts.ready.then(layoutAll);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
