<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Cat Image'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Cats'), ['controller' => 'Cats', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Cat'), ['controller' => 'Cats', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="catImages index large-9 medium-8 columns content">
    <h3><?= __('Cat Images') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('cats_id') ?></th>
                <th><?= $this->Paginator->sort('users_id') ?></th>
                <th><?= $this->Paginator->sort('image') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($catImages as $catImage): ?>
            <tr>
                <td><?= $this->Number->format($catImage->id) ?></td>
                <td><?= $catImage->has('cat') ? $this->Html->link($catImage->cat->id, ['controller' => 'Cats', 'action' => 'view', $catImage->cat->id]) : '' ?></td>
                <td><?= $this->Number->format($catImage->users_id) ?></td>
                <td><img src="<?= $catImage->url ?>" width="64px"></img></td>
                <td><?= h($catImage->created) ?></td>
                <td><?= h($catImage->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $catImage->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $catImage->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $catImage->id], ['confirm' => __('Are you sure you want to delete # {0}?', $catImage->id)]) ?>
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
