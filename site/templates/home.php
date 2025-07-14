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

<nav id="homenav">
    <!-- <a class="pink" href="<?= $site->url() ?>"> -->
      <!-- <h2>Mae West</h2> -->
    <!-- </a> -->
  <h4>
    <?php foreach ($site->children()->listed() as $pagename): ?>
      
        <a class="pink" href="<?= $pagename->url() ?>">
          <?= $pagename->title()->esc() ?></a>,
      
    <?php endforeach ?>

      
        <a class="blue" href="https://instagram.com/starring.maewest">
          instagram
        </a>
      </h4>
</nav>

<div id="toplogodiv">
  <img src="/assets/images/MWlogo_castiron.png" alt="Mae West logo" id="logo">
</div>

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

<div class="titleblock">
  <img src="/assets/images/bookend_left.png" id="bookendimg">
<div class="current-title-box">
  <h3 id="current-title"></h3>
</div>
<img src="/assets/images/bookend_right.png" id="bookendimg">
</div>

<?php $workspage = page('works'); ?>  
<?php $item = $workspage->children()->listed()->flip() ?>



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