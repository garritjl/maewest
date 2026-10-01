<?php
/*
  Snippets are a great way to store code snippets for reuse
  or to keep your templates clean.

  This header snippet is reused in all templates.
  It fetches information from the `site.txt` content file
  and contains the site navigation.

  More about snippets:
  https://getkirby.com/docs/guide/templates/snippets
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">

  <title><?= $site->title()->esc() ?></title>

  <link href="/style.css" rel="stylesheet" type="text/css" media="all">
  <link rel="stylesheet" href="https://use.typekit.net/sak3gzo.css">

  <?= css([
    'assets/css/post.css',
    '@auto'
  ]) ?>

  
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">

<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">

</head>
<body>

  

  <main class="main">

<div id="toplogodiv">
  <a href="<?= $site->url() ?>"><img src="/assets/images/MWlogo_castiron.png" alt="Mae West logo" id="logo"></a>
</div>

<nav id="postpagenav">

  <span class="navribbon" aria-hidden="true">
    <svg class="ribbon-end ribbon-left">
      <line x1="12" y1="0" x2="0" y2="0"></line>
      <line class="ribbon-v" x1="0" y1="0" x2="12" y2="50%"></line>
      <line class="ribbon-v" x1="12" y1="50%" x2="0" y2="100%"></line>
      <line class="ribbon-bot" x1="0" y1="100%" x2="12" y2="100%"></line>
    </svg>
    <svg class="ribbon-mid">
      <line x1="0" y1="0" x2="100%" y2="0"></line>
      <line x1="0" y1="100%" x2="100%" y2="100%"></line>
    </svg>
    <svg class="ribbon-end ribbon-right">
      <line x1="0" y1="0" x2="12" y2="0"></line>
      <line class="ribbon-v" x1="12" y1="0" x2="0" y2="50%"></line>
      <line class="ribbon-v" x1="0" y1="50%" x2="12" y2="100%"></line>
      <line class="ribbon-bot" x1="12" y1="100%" x2="0" y2="100%"></line>
    </svg>
  </span>

  <div id="navtext">
    <?php $children = $site->children()->listed(); ?>
    <?php foreach ($children as $pagename): ?>
      <?php if (!$children->first()->is($pagename)): ?>
        <span class="navstar" aria-hidden="true">⍟</span>
      <?php endif ?>
      <a class="navitem pink" href="<?= $pagename->url() ?>"><?= $pagename->title()->esc() ?></a>
    <?php endforeach ?>
  </div>
</nav>

<script>
(function () {
  var nav = document.getElementById('postpagenav');
  var navtext = document.getElementById('navtext');
  if (!nav || !navtext) return;

  var SEP = '\u00a0\u00a0';
  var DOT = '\u2022';
  var STAR = '\u235f';

  var items = [].slice.call(navtext.querySelectorAll('.navitem'));
  if (!items.length) return;
  items.forEach(function (el) { el.parentNode.removeChild(el); });

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
    var h = nav.getBoundingClientRect().height;
    if (!h) return;
    nav.querySelectorAll('.ribbon-end').forEach(function (svg) {
      var vs = svg.querySelectorAll('.ribbon-v');
      if (vs.length === 2) {
        vs[0].setAttribute('y2', h / 2);
        vs[1].setAttribute('y1', h / 2);
        vs[1].setAttribute('y2', h);
      }
      svg.querySelectorAll('.ribbon-bot').forEach(function (l) {
        l.setAttribute('y1', h);
        l.setAttribute('y2', h);
      });
    });
  }

  function layout() {
    var containerW = navtext.getBoundingClientRect().width;
    if (!containerW) return;
    var m = measure();
    build(computeLines(containerW, m.widths, m.starTotal));
    fillDots();
    syncRibbon();
  }

  window.addEventListener('resize', layout);
  if (document.readyState === 'complete') layout();
  else window.addEventListener('load', layout);
  if (document.fonts && document.fonts.ready) document.fonts.ready.then(layout);
})();
</script>

<article id="mainblock">

<div id="titleblock">
        <h1><?= $page->title()->kti() ?></h1>
        <h2><?= $page->subtitle()->kti() ?></h2>
        <h4>#<?= $page->number()->kti()?> — <?= $page->date()->toDate(new IntlDateFormatter("fr_FR", IntlDateFormatter::LONG, IntlDateFormatter::NONE, 'Europe/Berlin')) ?></h4>
    </div>

    <br>
    <div class="fourstripe">
        <hr>
        <hr>
        <hr>
        <hr>
    </div>

        <?php
          $pics   = $page->pics()->toFiles();
          $poster = $page->poster()->toFile();

          
          $gallery = $poster ? $pics->not($poster) : $pics;
          $default = $gallery->first() ?? $poster;
          $thumbs  = $poster && $pics->has($poster) ? $gallery->clone()->add($poster) : $gallery;

          $hasCaptions = $thumbs->filter(fn ($image) => $image->caption()->isNotEmpty())->isNotEmpty();
        ?>

        <div id="gallery">

            <div id="gallerywindow"<?= $hasCaptions ? ' class="has-captions"' : '' ?>>
                <?php if ($default): ?>
                  <a id="expandedImgLink" href="<?= $default->url() ?>"><img id="expandedImg" src="<?= $default->url() ?>" alt="<?= $default->alt()->esc() ?>"></a>
                <?php endif ?>

                <div id="imgtext"><?= $default ? $default->caption()->kti() : '' ?></div>
            </div>

            <div id="thumbs">
                <?php foreach ($thumbs as $image): ?>

                        <!-- <a href="<?= $image->url() ?>"> -->
                            <img src="<?= $image->url() ?>" id="gallerythumb"<?= $default && $image->is($default) ? ' class="is-selected"' : '' ?> alt="<?= $image->alt()->esc() ?>" data-caption="<?= esc($image->caption()->kti(), 'attr') ?>" onclick="selectImg(this);">
                        <!-- </a> -->
                    
                <?php endforeach ?>
            </div>
        </div>

        <script>

var expandImg = document.querySelector("#gallerywindow #expandedImg");
var imgText = document.getElementById("imgtext");
var imgLink = document.getElementById("expandedImgLink");


function syncCaptionWidth() {
  if (!expandImg || !imgText) return;
  imgText.style.width = expandImg.getBoundingClientRect().width + "px";
}

function selectImg(imgs) {
  expandImg.src = imgs.src;
  imgText.innerHTML = imgs.dataset.caption || "";
  expandImg.parentElement.style.display = "block";
  imgLink.href = imgs.src; // Set the link to the full image

  var thumbs = document.querySelectorAll("#thumbs #gallerythumb");
  for (var i = 0; i < thumbs.length; i++) {
    thumbs[i].classList.toggle("is-selected", thumbs[i] === imgs);
  }
}

if (expandImg) {
  expandImg.addEventListener("load", syncCaptionWidth);
  window.addEventListener("resize", syncCaptionWidth);
  if (expandImg.complete) syncCaptionWidth();
}
</script>

        
<br>
    <div class="fourstripe">
        <hr>
        <hr>
        <hr>
        <hr>
    </div>

        <p class="postdescription" id="description">
            <?= $page->description()->kti() ?>
        </p>

        <br>

        <p class="postdescription" id="textede">
          <?= $page->textede()->kti() ?>
        </p>

      <?php if ($page->footnotes()->isNotEmpty()): ?>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>

        <p class="postdescription" >
          <?= $page->footnotes()->kti() ?>
        </p>
      <?php endif ?>

        <br>

      <?php if ($page->map()->isNotEmpty()): ?>
        <div id="mapcontainer">
          <div id="mapimagewindow">
            <a href="<?= $page->map()->toFile()->url() ?>"><img id="expandedImg" src="<?= $page->map()->toFile()->url() ?>"></a>
          </div>
        </div>
      <?php endif ?>

      
<!-- <div id="backbut">
        <a href="javascript:history.back()">
        <img src="/content/backbut.svg" alt="back button" height="227" width="204">
        </a>
</div> -->

    </article>

<nav id="arrows" aria-label="Previous and next project links">
  <div id="prevnext">
    <?php if ($page->hasPrevListed()): ?>
      <a class="scriptlink prev-arrow" href="<?= $page->prevListed()->url() ?>">
        <img src="/assets/images/leftarrow_iron.png" alt="Previous project" class="nav-arrow-img">
      </a>
    <?php else: ?>
      <span class="nav-arrow-placeholder" aria-hidden="true"></span>
    <?php endif ?>

    <?php if ($page->hasNextListed()): ?>
      <a class="scriptlink next-arrow" href="<?= $page->nextListed()->url() ?>">
        <img src="/assets/images/rightarrow_iron.png" alt="Next project" class="nav-arrow-img">
      </a>
    <?php else: ?>
      <span class="nav-arrow-placeholder" aria-hidden="true"></span>
    <?php endif ?>
  </div>
</nav>

<!-- <div id="lightswitch">
    <img src="/assets/images/lightswitch_right.png" alt="lightswitch" height="240" width="109">
</div> -->
<?php snippet('footer2') ?>