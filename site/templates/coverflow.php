<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">

  <title><?= $site->title()->esc() ?></title>

  <link href="assets/css/coverflow.css" rel="stylesheet" type="text/css" media="all">
  <link rel="stylesheet" href="https://use.typekit.net/sak3gzo.css">

  <?= css([
    'assets/css/coverflow.css',
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
<?php $workspage = page('works'); ?>  
<?php $item = $workspage->children()->listed()->flip() ?>


<div class="boxes">
<?php foreach ($item as $item): ?>
    <?php if ($cover = $item->cover()->toFile()): ?>
  <div class="box" ><span><?= $item->number()->esc() ?></span><a <?php e($item->isOpen(), 'aria-current="page"') ?> href="<?= $item->url() ?>"><img src="<?= $cover->resize(850)->url() ?>"></a></div>
  <?php endif ?>
  <?php endforeach ?>
  
  <div class="controls">
    <button class="next"><span>Previous album</span>
      <svg viewBox="0 0 448 512" width="100" title="Previous Album">
        <path d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"></path>
      </svg>
    </button>
    <button class="prev"><span>Next album</span>
      <svg viewBox="0 0 448 512" width="100" title="Next Album">
        <path d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"></path>
      </svg>
    </button>
  </div>
</div>
<svg class="scroll-icon" viewBox="0 0 24 24">
  <path fill="currentColor" d="M20 6H23L19 2L15 6H18V18H15L19 22L23 18H20V6M9 3.09C11.83 3.57 14 6.04 14 9H9V3.09M14 11V15C14 18.3 11.3 21 8 21S2 18.3 2 15V11H14M7 9H2C2 6.04 4.17 3.57 7 3.09V9Z"></path>
</svg>
<div class="drag-proxy"></div>

<div id="current-title" class="current-title-box"></div>

<?php $workspage = page('works'); ?>  
<?php $item = $workspage->children()->listed()->flip() ?>

<ul>
    <?php foreach ($item as $item): ?>
  
      <li><p>#<?= $item->number()->esc() ?> <?= $item->title()->esc() ?></p></li>

  <?php endforeach ?>
</ul>

<?php $workspage = page('works'); ?>
<?php $item = $workspage->children()->listed()->flip(); ?>

<?php
$titles = [];
if ($item && !$item->isEmpty()) {
    foreach ($item as $child) {
        $titles[] = $child->title()->value(); // Fetch the raw string value of the title
    }
} else {
    $titles[] = "No items available"; // Fallback title
}
?>
<script>
  const carouselTitles = <?= json_encode($titles, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
</script>

<script type="module" src="assets/js/coverflow.js"></script>

<?php snippet('footer2.php') ?>