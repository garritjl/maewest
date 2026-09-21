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
    
  <p id="navtext">
    <?php $children = $site->children()->listed(); ?>
    <?php foreach ($children as $pagename): ?>
      <a class="pink" href="<?= $pagename->url() ?>">
        <?= $pagename->title()->esc() ?>
      </a>
      <?php if (!$children->last()->is($pagename)): ?>
        <span style="color: rgb(15, 15, 15); vertical-align: -1.5px;">⍟</span>
      <?php endif ?>
    <?php endforeach ?>

      
<!--         <a class="blue" href="https://instagram.com/starring.maewest">
          instagram
        </a> -->
    </p>
</nav>

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

        <p class="postdescription" >
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