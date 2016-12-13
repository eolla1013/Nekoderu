<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $eyewitness->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $eyewitness->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Eyewitnesses'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Cats'), ['controller' => 'Cats', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Cat'), ['controller' => 'Cats', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Eyewitness Images'), ['controller' => 'EyewitnessImages', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Eyewitness Image'), ['controller' => 'EyewitnessImages', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="eyewitnesses form large-9 medium-8 columns content">
    <?= $this->Form->create($eyewitness) ?>
    <fieldset>
        <legend><?= __('Edit Eyewitness') ?></legend>
        <?php
            echo $this->Form->input('user_id', ['options' => $users]);
            echo $this->Form->input('cat_id', ['options' => $cats]);
            echo $this->Form->input('content');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
