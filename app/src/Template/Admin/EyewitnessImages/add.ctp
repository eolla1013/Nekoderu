<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Eyewitness Images'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Eyewitnesses'), ['controller' => 'Eyewitnesses', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Eyewitness'), ['controller' => 'Eyewitnesses', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="eyewitnessImages form large-9 medium-8 columns content">
    <?= $this->Form->create($eyewitnessImage) ?>
    <fieldset>
        <legend><?= __('Add Eyewitness Image') ?></legend>
        <?php
            echo $this->Form->input('eyewitness_id', ['options' => $eyewitnesses]);
            echo $this->Form->input('url');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
