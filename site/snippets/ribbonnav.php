<span class="navribbon" aria-hidden="true">
  <svg class="ribbon-end ribbon-left">
    <polyline class="ribbon-outline" points="12,0 0,0 12,14.5 0,29 12,29"></polyline>
  </svg>
  <svg class="ribbon-mid">
    <line x1="0" y1="0" x2="100%" y2="0"></line>
    <line x1="0" y1="100%" x2="100%" y2="100%"></line>
  </svg>
  <svg class="ribbon-end ribbon-right">
    <polyline class="ribbon-outline" points="0,0 12,0 0,14.5 12,29 0,29"></polyline>
  </svg>
</span>

<div class="ribbonnav-text">
  <?php $children = $site->children()->listed(); ?>
  <?php foreach ($children as $pagename): ?>
    <?php if (!$children->first()->is($pagename)): ?>
      <span class="navstar" aria-hidden="true">⍟</span>
    <?php endif ?>
    <a class="navitem pink" href="<?= $pagename->url() ?>"><?= $pagename->title()->esc() ?></a>
  <?php endforeach ?>
</div>
