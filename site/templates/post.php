<?php snippet('header2') ?>

<article>
        <h1><?= $page->title()->kti() ?></h1>
        <h2><?= $page->subtitle()->kti() ?></h2>
        <h4><?= $page->date()->toDate('D M Y') ?></h4>

        <div class="gallery">
            <ul>
                <?php foreach ($page->pics()->toFiles() as $image): ?>
                    <li>
                        <a href="<?= $image->url() ?>">
                            <img src="<?= $image->url() ?>">
                        </a>
                        <?php if ($image->caption()->isNotEmpty()): ?>
                        <h5 class="caption"> 
                            <svg id="b" data-name="Layer 2" xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 275.542 250.613" fill="rgb(180, 180, 180)">
                                <g id="c" data-name="Layer 1">
                                <path d="M174.666,66.613c-25.737,14.328-44.516,30.438-57.715,47.599-.566.035-1.124.059-1.66.059,0,0-95.784-1.944-39.64-114.271C75.651,0,0,26.693,0,102.382s52.411,97.627,76.703,101.982c5.158.925,9.981,1.665,14.547,2.232,1.206,12.869,3.969,25.653,7.842,38.111,0,0,92.565,30.188,176.449-45.925,0,0-74.823-47.361-100.875-132.168Z" stroke-width="0"/>
                                </g>
                            </svg>&nbsp;<?= $image->caption()->esc() ?></h5>
                        <?php endif ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>

        <p class="marginleft" >
            <?= $page->description()->kti() ?>
        </p>

        
        <?php if ($page->hasChildren()) snippet('listpagechildren')?>


        <br>
<div id="tags">
        <p class="tagspindent">
            <strong>tags:</strong> 
            <?php foreach($page->tags()->split() as $tag): ?>
                <a class="taglink" href="<?= url('works', ['params' => ['tag' => $tag]]) ?>">
                <?= html($tag) ?></a>&nbsp;
            <?php endforeach ?>
        </p>
</div>
        <a href="javascript:history.back()">
        <img src="/content/backbut.svg" alt="back button" height="151" width="136">
        </a>
    </article>
<?php snippet('footer2') ?>