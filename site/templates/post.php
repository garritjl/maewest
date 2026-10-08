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

  <link rel="stylesheet" href="https://use.typekit.net/sak3gzo.css">

  <?= css([
    assetv('assets/css/post.css'),
    assetv('assets/css/nav.css'),
    assetv('assets/css/arrows.css'),
    '@auto'
  ]) ?>

  
<link rel="apple-touch-icon" sizes="180x180" href="<?= assetv('/apple-touch-icon.png') ?>">
<link rel="icon" type="image/png" sizes="48x48" href="<?= assetv('/favicon-48x48.png') ?>">
<link rel="icon" type="image/png" sizes="32x32" href="<?= assetv('/favicon-32x32.png') ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?= assetv('/favicon-16x16.png') ?>">
<link rel="icon" href="<?= assetv('/favicon.ico') ?>" sizes="any">
<link rel="manifest" href="<?= assetv('/site.webmanifest') ?>">

<meta name="msapplication-TileColor" content="#c755c1">
<meta name="theme-color" content="#ffffff">

</head>
<body>

  

  <main class="main">

<div id="toplogodiv">
  <a href="<?= $site->url() ?>"><img src="<?= assetv('/assets/images/MWlogo_castiron.png') ?>" alt="Mae West logo" id="logo"></a>
</div>

<nav id="postpagenav" class="ribbonnav">
  <?php snippet('ribbonnav') ?>
</nav>

<?= js(assetv('assets/js/nav.js')) ?>

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
                <div id="galleryviewport">
                  <?php if ($default): ?>
                    <a id="expandedImgLink" href="<?= $default->url() ?>"><img id="expandedImg" src="<?= $default->url() ?>" alt="<?= $default->alt()->esc() ?>"></a>
                  <?php endif ?>
                  <button type="button" class="galleryzone zone-prev" aria-label="Previous image"></button>
                  <button type="button" class="galleryzone zone-next" aria-label="Next image"></button>
                  <div class="zoneband band-prev" aria-hidden="true"></div>
                  <div class="zoneband band-next" aria-hidden="true"></div>
                </div>

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
  if (window.matchMedia("(orientation: portrait)").matches) {
    imgText.style.width = "";
    return;
  }
  imgText.style.width = expandImg.getBoundingClientRect().width + "px";
}

function syncViewportHeight() {
  var box = document.getElementById("galleryviewport");
  if (!box) return;

  if (!window.matchMedia("(max-width: 700px)").matches) {
    box.style.height = "";
    return;
  }

  var thumbs = galleryThumbs();
  if (!thumbs.length) return;

  var current = null;

  for (var i = 0; i < thumbs.length; i++) {
    if (thumbs[i].classList.contains("is-selected")) {
      current = thumbs[i];
      break;
    }
  }

  if (!current || !current.naturalWidth || !current.naturalHeight) return;

  var limit = 0.95 * document.documentElement.clientWidth;
  var width = box.getBoundingClientRect().width;
  var scale = Math.min(1, width / current.naturalWidth, limit / current.naturalHeight);

  box.style.height = Math.ceil(current.naturalHeight * scale) + "px";
}

function syncCaptionHeight() {
  var box = document.getElementById("galleryviewport");
  if (!imgText || !box) return;

  var thumbs = galleryThumbs();
  if (!thumbs.length) return;

  var rect = box.getBoundingClientRect();
  var portrait = window.matchMedia("(orientation: portrait)").matches;
  var savedHTML = imgText.innerHTML;
  var savedWidth = imgText.style.width;

  imgText.style.height = "auto";

  var tallest = 0;

  for (var i = 0; i < thumbs.length; i++) {
    var thumb = thumbs[i];
    var width = rect.width;

    if (!portrait && thumb.naturalWidth && thumb.naturalHeight) {
      var scale = Math.min(
        1,
        rect.width / thumb.naturalWidth,
        rect.height / thumb.naturalHeight
      );
      width = thumb.naturalWidth * scale;
    }

    imgText.style.width = width + "px";
    imgText.innerHTML = thumb.dataset.caption || "";
    if (imgText.scrollHeight > tallest) tallest = imgText.scrollHeight;
  }

  imgText.innerHTML = savedHTML;
  imgText.style.width = savedWidth;

  var lineHeight = parseFloat(window.getComputedStyle(imgText).lineHeight);

  if (lineHeight && tallest > lineHeight * 3 + 1) {
    imgText.style.height = "auto";
  } else {
    imgText.style.height = tallest + "px";
  }
}

function syncCaption() {
  syncViewportHeight();
  syncCaptionWidth();
  syncCaptionHeight();
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

  syncViewportHeight();
}

function syncZoneBands() {
  [
    ["zone-prev", "band-prev"],
    ["zone-next", "band-next"]
  ].forEach(function (pair) {
    var source = document.querySelector("." + pair[0]);
    var band = document.querySelector("." + pair[1]);
    if (!band) return;
    if (!source) {
      band.style.display = "none";
      return;
    }
    var rect = source.getBoundingClientRect();
    band.style.display = "";
    band.style.left = rect.left + "px";
    band.style.width = rect.width + "px";
  });

}

if (expandImg) {
  expandImg.addEventListener("load", syncCaption);
  window.addEventListener("resize", syncCaption);
  window.addEventListener("load", syncCaption);
  if (expandImg.complete) syncCaption();
}

window.addEventListener("resize", syncZoneBands);
if (document.readyState === "complete") syncZoneBands();
else window.addEventListener("load", syncZoneBands);

function galleryThumbs() {
  return [].slice.call(document.querySelectorAll("#thumbs #gallerythumb"));
}

function step(delta) {
  var thumbs = galleryThumbs();
  if (!thumbs.length) return;
  var current = 0;
  for (var i = 0; i < thumbs.length; i++) {
    if (thumbs[i].classList.contains("is-selected")) {
      current = i;
      break;
    }
  }
  selectImg(thumbs[(current + delta + thumbs.length) % thumbs.length]);
}

var viewport = document.getElementById("galleryviewport");
var zonePrev = document.querySelector(".zone-prev");
var zoneNext = document.querySelector(".zone-next");
var swiped = false;
var SWIPE_MIN = 40;

if (zonePrev) {
  zonePrev.addEventListener("click", function () {
    if (swiped) return;
    step(-1);
  });
}

if (zoneNext) {
  zoneNext.addEventListener("click", function () {
    if (swiped) return;
    step(1);
  });
}

document.addEventListener("touchstart", function () {}, { passive: true });

document.addEventListener("keydown", function (e) {
  if (e.defaultPrevented || e.metaKey || e.ctrlKey || e.altKey || e.shiftKey) return;
  if (e.key !== "ArrowLeft" && e.key !== "ArrowRight") return;

  var el = e.target;
  var tag = el && el.tagName ? el.tagName.toLowerCase() : "";
  if (tag === "input" || tag === "textarea" || tag === "select" || (el && el.isContentEditable)) return;

  if (!galleryThumbs().length) return;

  step(e.key === "ArrowLeft" ? -1 : 1);
  e.preventDefault();
});

if (viewport) {
  var startX = 0;
  var startY = 0;

  viewport.addEventListener("touchstart", function (e) {
    if (e.touches.length !== 1) return;
    swiped = false;
    startX = e.touches[0].clientX;
    startY = e.touches[0].clientY;
  }, { passive: true });

  viewport.addEventListener("touchend", function (e) {
    if (!e.changedTouches.length) return;
    var dx = e.changedTouches[0].clientX - startX;
    var dy = e.changedTouches[0].clientY - startY;
    if (Math.abs(dx) > SWIPE_MIN && Math.abs(dx) > Math.abs(dy)) {
      swiped = true;
      step(dx < 0 ? 1 : -1);
    }
  }, { passive: true });

  viewport.addEventListener("click", function (e) {
    if (!swiped) return;
    e.preventDefault();
    e.stopPropagation();
    swiped = false;
  }, true);
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
            <img class="mapimg" src="<?= $page->map()->toFile()->url() ?>" alt="The exhibition's floor plan">
          </div>
        </div>
      <?php endif ?>

      
<!-- <div id="backbut">
        <a href="javascript:history.back()">
        <img src="/content/backbut.svg" alt="back button" height="227" width="204">
        </a>
</div> -->
<?php $textepdf = $page->textepdf()->toFiles() ?>
<?php if ($textepdf->isNotEmpty()): ?>
  <nav id="textepdfnav" class="ribbonnav">
    <?php snippet('ribbonframe') ?>

    <div class="ribbonnav-text">
      <?php if ($textepdf->count() === 1): ?>
        <a class="navitem pink" href="<?= $textepdf->first()->url() ?>" download>texte &amp; plan de salle</a>
      <?php else: ?>
        <a class="navitem pink" href="<?= $page->url() ?>/texte.zip">texte &amp; plan de salle</a>
      <?php endif ?>
    </div>
  </nav>
<?php endif ?>

    </article>

<nav id="arrows" aria-label="Previous and next exhibition links">
  <div id="prevnext">
    <?php if ($page->hasPrevListed()): ?>
      <a class="scriptlink prev-arrow" href="<?= $page->prevListed()->url() ?>">
        <img src="<?= assetv('/assets/images/leftarrow_iron.png') ?>" alt="Previous exhibition" class="nav-arrow-img">
      </a>
    <?php else: ?>
      <span class="nav-arrow-placeholder" aria-hidden="true"></span>
    <?php endif ?>

    <?php if ($page->hasNextListed()): ?>
      <a class="scriptlink next-arrow" href="<?= $page->nextListed()->url() ?>">
        <img src="<?= assetv('/assets/images/rightarrow_iron.png') ?>" alt="Next exhibition" class="nav-arrow-img">
      </a>
    <?php else: ?>
      <span class="nav-arrow-placeholder" aria-hidden="true"></span>
    <?php endif ?>
  </div>
</nav>

<div class="arrowband band-arrow-prev" aria-hidden="true"></div>
<div class="arrowband band-arrow-next" aria-hidden="true"></div>

<?= js(assetv('assets/js/arrows.js')) ?>


<div id="tilescontainer">
  <img src="<?= assetv('/assets/images/tilefooter.jpg') ?>" id="tilefooter" alt="Black and white floor tiles with embossed letter reading: 'MAE WEST  EST. 2025  LAUSANNE, SUISSE.'">
</div>

  </main>

  <footer class="footerpostpage">
    <div>
      <?= $site->footer()->esc() ?>
    </div>
  </footer>

</body>
</html>