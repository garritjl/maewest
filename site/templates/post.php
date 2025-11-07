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

  </header>

  <main class="main">

<div id="toplogodiv">
  <a href="<?= $site->url() ?>"><img src="/assets/images/MWlogo_castiron.png" alt="Mae West logo" id="logo"></a>
</div>

<nav id="postpagenav">
    
  <p id="navtext">
    <?php foreach ($site->children()->listed() as $pagename): ?>
      
        <a class="pink" href="<?= $pagename->url() ?>">
          <?= $pagename->title()->esc() ?></a>,
          
    <?php endforeach ?>

      
        <a class="blue" href="https://instagram.com/starring.maewest">
          instagram
        </a>
    </p>
</nav>

<article id="mainblock">

<div id="titleblock">
        <h1><?= $page->title()->kti() ?></h1>
        <h2><?= $page->subtitle()->kti() ?></h2>
        <h4>#<?= $page->number()->kti()?> — <?= $page->date()->toDate('d M Y') ?></h4>
    </div>

    <br>
    <div class="fourstripe">
        <hr>
        <hr>
        <hr>
        <hr>
    </div>

        <div id="gallery">

            <div id="gallerywindow">
                <a id="expandedImgLink" href="<?= $page->poster()->toFile()->url() ?>"><img id="expandedImg" src="<?= $page->poster()->toFile()->url() ?>"></a>

                <div id="imgtext"></div>
            </div>

            <div id="thumbs">
                <?php foreach ($page->pics()->toFiles() as $image): ?>
                    
                        <!-- <a href="<?= $image->url() ?>"> -->
                            <img src="<?= $image->url() ?>" id="gallerythumb" onclick="selectImg(this);">
                        <!-- </a> -->
                    
                <?php endforeach ?>
            </div>
        </div>

        <script>
function selectImg(imgs) {
  var expandImg = document.getElementById("expandedImg");
  var imgText = document.getElementById("imgtext");
  var imgLink = document.getElementById("expandedImgLink");
  expandImg.src = imgs.src;
  imgText.innerHTML = imgs.alt;
  expandImg.parentElement.style.display = "block";
    imgLink.href = imgs.src; // Set the link to the full image
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

        
        <?php if ($page->hasChildren()) snippet('listpagechildren')?>


        <br>

        <a href="javascript:history.back()">
        <img src="/content/backbut.svg" alt="back button" height="151" width="136">
        </a>
    </article>
<?php snippet('footer2') ?>