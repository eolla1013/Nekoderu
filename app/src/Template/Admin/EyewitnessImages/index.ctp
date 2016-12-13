<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Eyewitness Image'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Eyewitnesses'), ['controller' => 'Eyewitnesses', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Eyewitness'), ['controller' => 'Eyewitnesses', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="eyewitnessImages index large-9 medium-8 columns content">
    <h3><?= __('Eyewitness Images') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('eyewitness_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($eyewitnessImages as $eyewitnessImage): ?>
            <tr>
                <td><?= $this->Number->format($eyewitnessImage->id) ?></td>
                <td><?= $eyewitnessImage->has('eyewitness') ? $this->Html->link($eyewitnessImage->eyewitness->id, ['controller' => 'Eyewitnesses', 'action' => 'view', $eyewitnessImage->eyewitness->id]) : '' ?></td>
                <td><?= h($eyewitnessImage->created) ?></td>
                <td><?= h($eyewitnessImage->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $eyewitnessImage->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $eyewitnessImage->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $eyewitnessImage->id], ['confirm' => __('Are you sure you want to delete # {0}?', $eyewitnessImage->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
