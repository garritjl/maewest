<?php snippet('header2') ?>

<div id="toplogodiv">
  <a href="<?= $site->url() ?>"><img src="/assets/images/MWlogo_castiron.png" alt="Mae West logo" id="logo"></a>
</div>

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