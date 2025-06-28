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

<!--
  <header class="header">
    <span class="logo">
    <a class="green" href="<?= $site->url() ?>">
      <?= $site->title()->esc() ?>
    </a>
  </span>
  -->

  <nav class="navlinks">

      <span>

      <a class="green" href="<?= $site->url() ?>">
      <img style="margin-bottom: -1.8pt; margin-right: 3pt; margin-left: -1pt;" src="/favicon-16x16.png" ><?= $site->title()->esc() ?></a>
      /
      <?php $featured = page('works')->children()->shuffle()->first(); ?>
      <a class="green" href="<?= $featured->url() ?>">random page</a>
      —
      
    

      <?php foreach ($site->children()->listed() as $item): ?>
      <a class="green" href="<?= $item->url() ?>">
      <?= $item->title()->esc() ?></a>,
      <?php endforeach ?>

    
      
    
      </span>

      

      <?php snippet('social') ?>
      <br>
  </nav>

  </header>

  <main class="main">
