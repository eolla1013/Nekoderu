<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Eyewitness Image'), ['action' => 'edit', $eyewitnessImage->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Eyewitness Image'), ['action' => 'delete', $eyewitnessImage->id], ['confirm' => __('Are you sure you want to delete # {0}?', $eyewitnessImage->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Eyewitness Images'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Eyewitness Image'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Eyewitnesses'), ['controller' => 'Eyewitnesses', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Eyewitness'), ['controller' => 'Eyewitnesses', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="eyewitnessImages view large-9 medium-8 columns content">
    <h3><?= h($eyewitnessImage->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Eyewitness') ?></th>
            <td><?= $eyewitnessImage->has('eyewitness') ? $this->Html->link($eyewitnessImage->eyewitness->id, ['controller' => 'Eyewitnesses', 'action' => 'view', $eyewitnessImage->eyewitness->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($eyewitnessImage->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($eyewitnessImage->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($eyewitnessImage->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Url') ?></h4>
        <?= $this->Text->autoParagraph(h($eyewitnessImage->url)); ?>
    </div>
</div>
