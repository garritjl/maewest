<?php snippet('header2') ?>

<article>

<?php $workspage = page('works'); ?>  
<?php $item = $workspage->children()->listed()->flip() ?>

        <h1><?= $page->title()->kti() ?></h1>

        <div>
            <?php foreach ($item as $item): ?>
                <div>
                    <ul>
                        <li>
                            <a class="pink" <?php e($item->isOpen(), 'aria-current="page"') ?> href="<?= $item->url() ?>">
                                #<?= $item->number()->kti() ?> <?= $item->title()->kti() ?> – <?= $item->subtitle()->kti() ?>
                            </a>
                        </li>
                </div>
            <?php endforeach ?>
        </div>

        <br>

        <a href="javascript:history.back()">
        <img src="/content/backbut.svg" alt="back button" height="151" width="136">
        </a>

</article>

<?php snippet('footer2') ?>