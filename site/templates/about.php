<?php snippet('header2') ?>

<article>
  <h1 class="txtpagetitle"><?= $page->title()->esc() ?></h1>
  <div class="text">
    <p><?= $page->text()->kt() ?></p>
  </div>
</article>

<?php snippet('footer2') ?>