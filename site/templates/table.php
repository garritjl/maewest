<?php snippet('header2') ?>

<article>

<?php $workspage = page('works'); ?>  
<?php $item = $workspage->children()->listed()->flip() ?>

        <h1 class="indextitle"><?= $page->title()->kti() ?></h1>

        <table class="styled-table">
            <tbody>
            <?php foreach ($item as $item): ?>
                <tr>

                    <td>
                        #
                    </td>

                    <td>
                        <?= $item->number()->kti() ?>
                    </td>

                    <td><a class="pink" <?php e($item->isOpen(), 'aria-current="page"') ?> href="<?= $item->url() ?>">
                        <?= $item->title()->kti() ?>
                    </a></td>

                    <td><a class="pink" <?php e($item->isOpen(), 'aria-current="page"') ?> href="<?= $item->url() ?>">
                        <?= $item->subtitle()->kti() ?>
                    </a></td>

                </tr>
            <?php endforeach ?>    
            </tbody>
        </table>




        <br>

        <a href="javascript:history.back()">
        <img src="/content/backbut.svg" alt="back button" height="151" width="136">
        </a>

</article>

<?php snippet('footer2') ?>