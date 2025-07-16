<?php snippet('header2') ?>

<div id="toplogodiv">
  <img src="/assets/images/MWlogo_castiron.png" alt="Mae West logo" id="logo">
</div>

<article id="mainblock">

        <div class="gallery">
            <div id="thumbs">
                <?php foreach ($page->pics()->toFiles() as $image): ?>
                    
                        <a href="<?= $image->url() ?>">
                            <img src="<?= $image->url() ?>" id="gallerythumb">
                        </a>
                    
                <?php endforeach ?>
                </div>
        </div>

        <div id="titleblock">
        <h1><?= $page->title()->kti() ?></h1>
        <h2><?= $page->subtitle()->kti() ?></h2>
        <h4><?= $page->date()->toDate('d M Y') ?></h4>
    </div>
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