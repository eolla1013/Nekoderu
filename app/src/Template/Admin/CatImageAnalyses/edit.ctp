<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $catImageAnalysis->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $catImageAnalysis->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Cat Image Analyses'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Cat Images'), ['controller' => 'CatImages', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Cat Image'), ['controller' => 'CatImages', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="catImageAnalyses form large-9 medium-8 columns content">
    <?= $this->Form->create($catImageAnalysis) ?>
    <fieldset>
        <legend><?= __('Edit Cat Image Analysis') ?></legend>
        <?php
            echo $this->Form->input('catImage_id', ['options' => $catImages]);
            echo $this->Form->input('analyzer');
            echo $this->Form->input('data');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
