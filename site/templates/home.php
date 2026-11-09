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
      <feFuncR type="table" tableValues="0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.780 0.738 0.696 0.654 0.612 0.628 0.640 0.650 0.659 0.667 0.676 0.683 0.691 0.698 0.705 0.712 0.719 0.726 0.732 0.739 0.745 0.751 0.757 0.763 0.769 0.775 0.781 0.787 0.792 0.798 0.804 0.809 0.815 0.820 0.825 0.831 0.836 0.840 0.845 0.849 0.853 0.857 0.862 0.866 0.870 0.874 0.878 0.882 0.886 0.890 0.894 0.898 0.902 0.906 0.910 0.914 0.918 0.922 0.926 0.930 0.933"/>
      <feFuncG type="table" tableValues="0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.333 0.407 0.480 0.554 0.627 0.645 0.656 0.667 0.676 0.685 0.694 0.702 0.710 0.717 0.724 0.732 0.739 0.746 0.752 0.759 0.765 0.772 0.778 0.784 0.791 0.797 0.803 0.809 0.815 0.820 0.826 0.832 0.837 0.843 0.849 0.854 0.859 0.863 0.867 0.871 0.875 0.879 0.883 0.887 0.891 0.894 0.898 0.902 0.906 0.909 0.913 0.917 0.920 0.924 0.928 0.931 0.935 0.938 0.942 0.946 0.949"/>
      <feFuncB type="table" tableValues="0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.757 0.721 0.684 0.648 0.612 0.628 0.639 0.649 0.658 0.667 0.675 0.682 0.690 0.697 0.704 0.711 0.717 0.724 0.730 0.737 0.743 0.749 0.755 0.761 0.767 0.772 0.778 0.784 0.789 0.795 0.800 0.806 0.811 0.816 0.822 0.827 0.832 0.836 0.840 0.844 0.849 0.853 0.857 0.861 0.865 0.869 0.873 0.876 0.880 0.884 0.888 0.892 0.896 0.899 0.903 0.907 0.911 0.914 0.918 0.922 0.925"/>
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

<?= js(assetv('assets/js/glitter.js')) ?>

<?= js(assetv('assets/js/shimmer.js')) ?>

<div id="address">
  <p id="address">Pré-Du-Marché 19 1004 Lausanne Suisse</p>
</div>

<?php snippet('footer2.php') ?>