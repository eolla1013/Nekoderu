<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Similarities'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="similarities form large-9 medium-8 columns content">
    <?= $this->Form->create($similarity) ?>
    <fieldset>
        <legend><?= __('Add Similarity') ?></legend>
        <?php
            echo $this->Form->input('cat1_id');
            echo $this->Form->input('cat2_id');
            echo $this->Form->input('answer');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
