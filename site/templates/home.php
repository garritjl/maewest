<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">

  <title><?= $site->title()->esc() ?></title>

  <?= css([
    assetv('assets/css/home.css'),
    assetv('assets/css/nav.css'),
    assetv('assets/css/arrows.css'),
    assetv('assets/css/templates/home.css')
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
<svg width="0" height="0" style="position: absolute" aria-hidden="true" focusable="false">
  <filter id="pinkmap" color-interpolation-filters="sRGB">
    <feColorMatrix type="matrix" values="0.2126 0.7152 0.0722 0 0  0.2126 0.7152 0.0722 0 0  0.2126 0.7152 0.0722 0 0  0 0 0 1 0"/>
    <feComponentTransfer>
      <feFuncR type="table" tableValues="0.169 0.780 0.925"/>
      <feFuncG type="table" tableValues="0.059 0.333 0.969"/>
      <feFuncB type="table" tableValues="0.165 0.757 0.933"/>
    </feComponentTransfer>
  </filter>
</svg>
<?php $workspage = page('works'); ?>  
<?php $item = $workspage->children()->listed() ?>

<nav id="homenav" class="ribbonnav">
  <?php snippet('ribbonnav') ?>
</nav>

<div id="toplogodiv">
  <img src="<?= assetv('/assets/images/MWlogo_castiron.png') ?>" alt="Mae West logo" id="logo">
</div>

<div class="boxes">
<?php foreach ($item as $item): ?>
  <?php if ($poster = $item->poster()->toFile()): ?>
    <div class="box" >
      <a <?php e($item->isOpen(), 'aria-current="page"') ?> href="<?= $item->url() ?>">
        <img
          src="<?= $poster->thumb(['width' => 1200, 'format' => 'webp'])->url() ?>"
          srcset="<?= $poster->srcset([
            '600w'  => ['width' => 600, 'format' => 'webp'],
            '900w'  => ['width' => 900, 'format' => 'webp'],
            '1200w' => ['width' => 1200, 'format' => 'webp'],
            '1600w' => ['width' => 1600, 'format' => 'webp'],
            '2000w' => ['width' => 2000, 'format' => 'webp'],
            '2400w' => ['width' => 2400, 'format' => 'webp'],
          ]) ?>"
          sizes="(orientation: portrait) min(108vw, 760px), min(400px, 56vh)"
          alt="<?= $item->title()->esc() ?>"
        >
      </a>
    </div>

  <?php endif ?>
<?php endforeach ?>
  

<!--   <div class="controls">

    <button class="next"><span>Previous album</span>
      <img src="<?= assetv('/assets/images/arrowdraft2.png') ?>" title="Previous Album">
      </img>
    </button>

    <button class="prev"><span>Next album</span>
    <img src="<?= assetv('/assets/images/arrowdraft2right.png') ?>" title="Next Album">
    </img>
    </button>

  </div> -->
</div>

<div class="drag-proxy"></div>

<div class="vignette"></div>

<div class="titleblock">
  <button class="next prev-arrow">
    <img src="<?= assetv('/assets/images/leftarrow_iron.png') ?>" id="bookendimg">
  </button>

<div class="current-title-box">
  <h2 id="current-title"></h2>
  
  <h3 id="current-subtitle"></h3>

  <h5>#<span id="current-number"></span> – <span id="current-date">5 Nov 2025</span></h5>
</div>

<button class="prev next-arrow">
<img src="<?= assetv('/assets/images/rightarrow_iron.png') ?>" id="bookendimg">
</button>
</div>

<div class="arrowband band-arrow-prev" aria-hidden="true"></div>
<div class="arrowband band-arrow-next" aria-hidden="true"></div>

<?php $workspage = page('works'); ?>  
<?php $item = $workspage->children()->listed() ?>



<?php $workspage = page('works'); ?>
<?php $item = $workspage->children()->listed(); ?>

<?php
$titles = [];
if ($item && !$item->isEmpty()) {
    foreach ($item as $child) {
        $titles[] = $child->title()->value();
    }
} else {
    $titles[] = "No items available";
}
?>

<?php
$subtitles = [];
if ($item && !$item->isEmpty()) {
    foreach ($item as $child) {
        $subtitles[] = $child->subtitle()->value();
    }
} else {
    $subtitles[] = "No items available";
}
?>

<?php
$dates = [];
if ($item && !$item->isEmpty()) {
    foreach ($item as $child) {
        $dates[] = $child->date()->toDate(new IntlDateFormatter("fr_FR", IntlDateFormatter::LONG, IntlDateFormatter::NONE, 'Europe/Berlin'));
    }
} else {
    $dates[] = "No items available";
}
?>

<?php
$numbers = [];
if ($item && !$item->isEmpty()) {
    foreach ($item as $child) {
        $numbers[] = $child->number()->value();
    }
} else {
    $numbers[] = "No items available";
}
?>

<script>
  const carouselTitles = <?= json_encode($titles, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
  const carouselSubtitles = <?= json_encode($subtitles, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
  const carouselDates = <?= json_encode($dates, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
  const carouselNumbers = <?= json_encode($numbers, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
</script>

<script type="module" src="<?= assetv('assets/js/coverflow.js') ?>"></script>

<?= js(assetv('assets/js/nav.js')) ?>

<?= js(assetv('assets/js/arrows.js')) ?>

<?= js(assetv('assets/js/titlefit.js')) ?>

<div id="address">
  <p id="address">Pré-Du-Marché 19 1004 Lausanne Suisse</p>
</div>

<?php snippet('footer2.php') ?>