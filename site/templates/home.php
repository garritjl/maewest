<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">

  <title><?= $site->title()->esc() ?></title>

  <?= css([
    assetv('assets/css/home.css'),
    assetv('assets/css/nav.css'),
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
<?php $workspage = page('works'); ?>  
<?php $item = $workspage->children()->listed() ?>

<nav id="homenav" class="ribbonnav">
  <?php snippet('ribbonnav') ?>
</nav>

<div id="toplogodiv">
  <img src="/assets/images/MWlogo_castiron.png" alt="Mae West logo" id="logo">
</div>

<div class="boxes">
<?php foreach ($item as $item): ?>
  <?php if ($poster = $item->poster()->toFile()): ?>
    <div class="box" >
      <a <?php e($item->isOpen(), 'aria-current="page"') ?> href="<?= $item->url() ?>">
        <img src="<?= $poster->resize(850)->url() ?>">
      </a>
    </div>

  <?php endif ?>
<?php endforeach ?>
  

<!--   <div class="controls">

    <button class="next"><span>Previous album</span>
      <img src="/assets/images/arrowdraft2.png" title="Previous Album">
      </img>
    </button>

    <button class="prev"><span>Next album</span>
    <img src="/assets/images/arrowdraft2right.png" title="Next Album">
    </img>
    </button>

  </div> -->
</div>

<div class="drag-proxy"></div>

<div class="vignette"></div>

<div class="titleblock">
  <button class="next">
    <img src="/assets/images/leftarrow_iron.png" id="bookendimg">
  </button>

<div class="current-title-box">
  <h2 id="current-title"></h2>
  
  <h3 id="current-subtitle"></h3>

  <h5>#<span id="current-number"></span> – <span id="current-date">5 Nov 2025</span></h5>
</div>

<button class="prev">
<img src="/assets/images/rightarrow_iron.png" id="bookendimg">
</button>
</div>

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

<div id="address">
  <p id="address">Pré-Du-Marché 19 1004 Lausanne Suisse</p>
</div>

<?php snippet('footer2.php') ?>