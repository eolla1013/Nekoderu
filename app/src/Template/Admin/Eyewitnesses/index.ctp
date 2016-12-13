<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Eyewitness'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Cats'), ['controller' => 'Cats', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Cat'), ['controller' => 'Cats', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Eyewitness Images'), ['controller' => 'EyewitnessImages', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Eyewitness Image'), ['controller' => 'EyewitnessImages', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="eyewitnesses index large-9 medium-8 columns content">
    <h3><?= __('Eyewitnesses') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cat_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($eyewitnesses as $eyewitness): ?>
            <tr>
                <td><?= $this->Number->format($eyewitness->id) ?></td>
                <td><?= $eyewitness->has('user') ? $this->Html->link($eyewitness->user->id, ['controller' => 'Users', 'action' => 'view', $eyewitness->user->id]) : '' ?></td>
                <td><?= $eyewitness->has('cat') ? $this->Html->link($eyewitness->cat->id, ['controller' => 'Cats', 'action' => 'view', $eyewitness->cat->id]) : '' ?></td>
                <td><?= h($eyewitness->created) ?></td>
                <td><?= h($eyewitness->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $eyewitness->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $eyewitness->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $eyewitness->id], ['confirm' => __('Are you sure you want to delete # {0}?', $eyewitness->id)]) ?>
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
