<ul class="marginleft">
    <?php foreach ($page->children()->listed()->flip() as $item): ?>
        <li>
            <a class="green" <?php e($item->isOpen(), 'aria-current="page"') ?> href="<?= $item->url() ?>">
                <?= $item->title()->esc() ?>      
            </a>
        </li>
    <?php endforeach ?>
</ul>