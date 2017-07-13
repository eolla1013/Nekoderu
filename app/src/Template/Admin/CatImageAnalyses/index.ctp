<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Cat Image Analysis'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Cat Images'), ['controller' => 'CatImages', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Cat Image'), ['controller' => 'CatImages', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="catImageAnalyses index large-9 medium-8 columns content">
    <h3><?= __('Cat Image Analyses') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('catImage_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('analyzer') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($catImageAnalyses as $catImageAnalysis): ?>
            <tr>
                <td><?= $this->Number->format($catImageAnalysis->id) ?></td>
                <td><?= $catImageAnalysis->has('cat_image') ? $this->Html->link($catImageAnalysis->cat_image->id, ['controller' => 'CatImages', 'action' => 'view', $catImageAnalysis->cat_image->id]) : '' ?></td>
                <td><?= h($catImageAnalysis->analyzer) ?></td>
                <td><?= h($catImageAnalysis->created) ?></td>
                <td><?= h($catImageAnalysis->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $catImageAnalysis->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $catImageAnalysis->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $catImageAnalysis->id], ['confirm' => __('Are you sure you want to delete # {0}?', $catImageAnalysis->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
