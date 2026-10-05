(function () {
  var SEP = '  ';
  var DOT = '•';
  var STAR = '⍟';
  var SATOSHI_ASCENT = 1.010;
  var SATOSHI_DESCENT = 0.240;
  var DRIFT_EPSILON = 0.05;

  function measuredBaseline(c) {
    var probe = document.createElement('span');
    probe.style.cssText =
      'position:absolute;left:-9999px;top:0;white-space:pre;font-family:' + c.fontFamily +
      ';font-size:' + c.fontSize + ';font-weight:' + c.fontWeight +
      ';font-style:' + c.fontStyle + ';line-height:' + c.lineHeight;
    probe.textContent = 'x';
    var mark = document.createElement('span');
    mark.style.cssText = 'display:inline-block;width:0;height:0';
    probe.appendChild(mark);
    document.body.appendChild(probe);
    var value = mark.getBoundingClientRect().bottom - probe.getBoundingClientRect().top;
    document.body.removeChild(probe);
    return value;
  }

  function baselineDrift(el) {
    var c = getComputedStyle(el);
    var fs = parseFloat(c.fontSize);
    var lh = parseFloat(c.lineHeight);
    if (!fs || !lh) return 0;
    var ideal = lh / 2 + (SATOSHI_ASCENT - SATOSHI_DESCENT) * fs / 2;
    var drift = ideal - measuredBaseline(c);
    return Math.abs(drift) < DRIFT_EPSILON ? 0 : drift;
  }

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

    function correctBaselines() {
      var textDrift = baselineDrift(navtext);
      var fill = navtext.querySelector('.navfill');
      var fillDrift = fill ? baselineDrift(fill) : textDrift;
      nav.style.setProperty('--navtext-fix', textDrift.toFixed(3) + 'px');
      nav.style.setProperty('--navfill-fix', (fillDrift - textDrift).toFixed(3) + 'px');
    }

    return function layout() {
      var containerW = navtext.getBoundingClientRect().width;
      if (!containerW) return;
      var m = measure();
      build(computeLines(containerW, m.widths, m.starTotal));
      correctBaselines();
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

  if (location.search.indexOf('navdebug') === -1) return;

  window.addEventListener('load', function () {
    var item = document.querySelector('.navitem');
    var fill = document.querySelector('.navfill');
    if (!item || !fill) return;

    var star = document.querySelector('.navstar');
    var ci = getComputedStyle(item);
    var cf = getComputedStyle(fill);
    var cs = star ? getComputedStyle(star) : cf;
    var fsI = parseFloat(ci.fontSize);
    var fsF = parseFloat(cf.fontSize);

    var ctx = document.createElement('canvas').getContext('2d');

    function useFont(c) {
      ctx.font = c.fontStyle + ' ' + c.fontWeight + ' ' + c.fontSize + ' ' + c.fontFamily;
    }

    // glyph extents relative to the baseline, measured as actually rendered
    function glyph(c, ch) {
      useFont(c);
      var m = ctx.measureText(ch);
      return {
        adv: m.width,
        top: m.actualBoundingBoxAscent,
        bot: -m.actualBoundingBoxDescent,
        fAsc: m.fontBoundingBoxAscent,
        fDesc: m.fontBoundingBoxDescent
      };
    }

    var gx = glyph(ci, 'x');
    var gd = glyph(cf, DOT);
    var gs = glyph(cs, STAR);

    // true ink extents: Safari clamps actualBoundingBoxDescent to 0, so scan pixels
    function ink(c, ch) {
      var S = 160, B = 120;
      var cv = document.createElement('canvas');
      cv.width = S;
      cv.height = S;
      var g = cv.getContext('2d');
      g.font = c.fontStyle + ' ' + c.fontWeight + ' ' + c.fontSize + ' ' + c.fontFamily;
      g.textBaseline = 'alphabetic';
      g.fillText(ch, 20, B);
      var d = g.getImageData(0, 0, S, S).data;
      var t = -1, b = -1;
      for (var y = 0; y < S; y++) {
        for (var x = 0; x < S; x++) {
          if (d[(y * S + x) * 4 + 3] > 8) {
            if (t < 0) t = y;
            b = y;
            break;
          }
        }
      }
      return t < 0 ? null : { top: B - t, bot: B - b, mid: B - (t + b) / 2 };
    }

    // the baseline the browser ACTUALLY lays out: a zero-height inline-block
    // sits with its bottom edge on the baseline
    function realBaseline(c) {
      var p = document.createElement('span');
      p.style.cssText = 'position:absolute;left:-9999px;top:0;white-space:pre;font-family:' +
        c.fontFamily + ';font-size:' + c.fontSize + ';font-weight:' + c.fontWeight +
        ';font-style:' + c.fontStyle + ';line-height:' + c.lineHeight;
      p.textContent = 'x';
      var m = document.createElement('span');
      m.style.cssText = 'display:inline-block;width:0;height:0';
      p.appendChild(m);
      document.body.appendChild(p);
      var v = m.getBoundingClientRect().bottom - p.getBoundingClientRect().top;
      document.body.removeChild(p);
      return v;
    }

    var blI = realBaseline(ci);
    var blF = realBaseline(cf);
    var blS = realBaseline(cs);
    var ix = ink(ci, 'x');
    var id = ink(cf, DOT);
    var is = ink(cs, STAR);

    var textCentre = blI - ix.mid;
    var dotCentre = blF - id.mid;
    var starCentre = blS - is.mid;

    var rows = [
      ['devicePixelRatio', window.devicePixelRatio],
      ['SATOSHI CHECK', ''],
      ['  x advance', gx.adv.toFixed(3) + 'px  (Satoshi: ' + (0.455 * fsI).toFixed(3) + ')'],
      ['  x height', gx.top.toFixed(3) + 'px  (Satoshi: ' + (0.484 * fsI).toFixed(3) + ')'],
      ['  dot advance', gd.adv.toFixed(3) + 'px  (Satoshi: ' + (0.376 * fsF).toFixed(3) + ')'],
      ['  dot top/bot', gd.top.toFixed(3) + ' / ' + gd.bot.toFixed(3) +
        '  (Satoshi: ' + (0.4 * fsF).toFixed(3) + ' / ' + (0.127 * fsF).toFixed(3) + ')'],
      ['BASELINES (real)', ''],
      ['  item baseline', blI.toFixed(3) + 'px  (desktop: 17.853, if rounded: 17.667)'],
      ['  fill baseline', blF.toFixed(3) + 'px  (desktop: 16.775, if rounded: 17.167)'],
      ['  dot ink t/m/b', id.top.toFixed(2) + ' / ' + id.mid.toFixed(2) + ' / ' + id.bot.toFixed(2)],
      ['RESULT', ''],
      ['  text x-centre', textCentre.toFixed(3) + 'px from line top'],
      ['  dot centre', dotCentre.toFixed(3) + 'px from line top'],
      ['  NEEDED top', (textCentre - dotCentre).toFixed(3) + 'px'],
      ['  applied top', cf.top],
      ['STAR', ''],
      ['  star advance', gs.adv.toFixed(3) + 'px'],
      ['  star centre', starCentre.toFixed(3) + 'px from line top'],
      ['  star NEEDED top', (textCentre - starCentre).toFixed(3) + 'px'],
      ['  star applied', cs.top]
    ];

    var box = document.createElement('div');
    box.style.cssText =
      'position:fixed;left:0;right:0;bottom:0;z-index:99999;background:#000;color:#0f0;' +
      'font:12px/1.5 monospace;padding:10px;white-space:pre;overflow:auto;max-height:60vh;' +
      '-webkit-text-size-adjust:100%;';
    box.textContent = rows
      .map(function (r) { return (r[0] + '                ').slice(0, 17) + r[1]; })
      .join('\n');
    document.body.appendChild(box);
  });
})();
