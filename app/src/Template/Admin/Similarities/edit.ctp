<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $similarity->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $similarity->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Similarities'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="similarities form large-9 medium-8 columns content">
    <?= $this->Form->create($similarity) ?>
    <fieldset>
        <legend><?= __('Edit Similarity') ?></legend>
        <?php
            echo $this->Form->input('cat1_id');
            echo $this->Form->input('cat2_id');
            echo $this->Form->input('answer');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
