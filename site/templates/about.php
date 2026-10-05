<?php snippet('header2') ?>

<article>
  <!-- <h1 class="txtpagetitle"><?= $page->title()->esc() ?></h1> -->
   <img src="<?= assetv('/assets/images/aproposv2.png') ?>" style="width: 229px; height: 92px; max-width: 100%; height: auto; margin-left: -4px;" alt="à propos">
  <div class="text">
    <?php if ($dropcap = $page->image()): ?>
      <img class="dropcap" src="<?= $dropcap->resize(null, 200)->url() ?>" alt="<?= $dropcap->alt()->esc() ?>">
    <?php endif ?>
    <p><?= $page->text()->kt() ?></p>
  </div>
</article>

<?php snippet('footer2') ?>