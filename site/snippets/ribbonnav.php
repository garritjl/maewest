<?php snippet('ribbonframe') ?>

<div class="ribbonnav-text">
  <?php $children = $site->children()->listed(); ?>
  <?php foreach ($children as $pagename): ?>
    <?php if (!$children->first()->is($pagename)): ?>
      <span class="navstar" aria-hidden="true">⍟</span>
    <?php endif ?>
    <a class="navitem pink" href="<?= $pagename->url() ?>"><?= $pagename->title()->esc() ?></a>
  <?php endforeach ?>
</div>
