<?php snippet('header2') ?>

<article>

<?php $workspage = page('works'); ?>  
<?php $item = $workspage->children()->listed()->flip() ?>

        <!-- <h1 class="indextitle"><?= $page->title()->kti() ?></h1> -->
        <img src="/assets/images/indexv2.png" width="214" height="104" style="max-width: 100%; height: auto; margin-left: -4px;" alt="index">

        <div class="indexlist">
            <?php foreach ($item as $item): ?>
                <a class="indexrow" <?php e($item->isOpen(), 'aria-current="page"') ?> href="<?= $item->url() ?>"><span class="indexnum"><span class="indexhash">#</span><?= $item->number()->kti() ?></span><span class="indexname"><?= $item->title()->kti() ?></span><span class="indexartist"><?= $item->subtitle()->kti() ?></span></a>
            <?php endforeach ?>
        </div>




        <br>

        <!-- <a href="javascript:history.back()">
        <img src="/content/backbut.svg" alt="back button" height="151" width="136">
        </a> -->

</article>

<?php snippet('footer2') ?>