<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">

  <title><?= $site->title()->esc() ?></title>

  <link href="assets/css/home.css" rel="stylesheet" type="text/css" media="all">

  <?= css([
    'assets/css/home.css',
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

<div id="toplogodiv"><img src="/assets/images/MWlogo_greybeads.png" alt="Mae West logo" id="logo"></div>

<div class="boxes">
<?php foreach ($item as $item): ?>
  <?php if ($cover = $item->cover()->toFile()): ?>
    <div class="box" >
      
      <a <?php e($item->isOpen(), 'aria-current="page"') ?> href="<?= $item->url() ?>">
        <img src="<?= $cover->resize(850)->url() ?>">
      </a>
    </div>
  <?php endif ?>
<?php endforeach ?>
  
  <div class="controls">

    <button class="next"><span>Previous album</span>
      <img src="/assets/images/arrowdraft2.png" title="Previous Album">
      </img>
    </button>

    <button class="prev"><span>Next album</span>
    <img src="/assets/images/arrowdraft2right.png" title="Next Album">
    </img>
    </button>

  </div>
</div>

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
        $titles[] = $child->title()->value();
    }
} else {
    $titles[] = "No items available";
}
?>

<script>
  const carouselTitles = <?= json_encode($titles, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
</script>

<script type="module" src="assets/js/coverflow.js"></script>

<?php snippet('footer2.php') ?>