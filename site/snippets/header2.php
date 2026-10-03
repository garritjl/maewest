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

<!--
  <header class="header">
    <span class="logo">
    <a class="pink" href="<?= $site->url() ?>">
      <?= $site->title()->esc() ?>
    </a>
  </span>
  -->

  <nav id="homenav">
    
  <p id="navtext">
    <a class="pink" href="<?= $site->url() ?>">Mae West</a>
    —
    <?php $children = $site->children()->listed(); ?>
    <?php foreach ($children as $pagename): ?>
      <a class="pink" href="<?= $pagename->url() ?>"><?= $pagename->title()->esc() ?></a>
      <?php if (!$children->last()->is($pagename)): ?>
        <span class="navstar" aria-hidden="true">⍟</span>
      <?php endif ?>
    <?php endforeach ?>

<!--         <a class="blue" href="https://instagram.com/starring.maewest">
          instagram
        </a> -->
    </p>
</nav>

  </header>

  <main class="main">
