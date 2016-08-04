<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Cat'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="cats index large-9 medium-8 columns content">
    <h3><?= __('Cats') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('time') ?></th>
                <th><?= $this->Paginator->sort('locate') ?></th>
                <th><?= $this->Paginator->sort('image_url') ?></th>
                <th><?= $this->Paginator->sort('flg') ?></th>
                <th><?= $this->Paginator->sort('status') ?></th>
                <th><?= $this->Paginator->sort('ear_shape') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cats as $cat): ?>
            <tr>
                <td><?= $this->Number->format($cat->id) ?></td>
                <td><?= $this->Number->format($cat->time) ?></td>
                <td><?= h($cat->locate) ?></td>
                <td><?= h($cat->image_url) ?></td>
                <td><?= $this->Number->format($cat->flg) ?></td>
                <td><?= $this->Number->format($cat->status) ?></td>
                <td><?= $this->Number->format($cat->ear_shape) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $cat->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $cat->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $cat->id], ['confirm' => __('Are you sure you want to delete # {0}?', $cat->id)]) ?>
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