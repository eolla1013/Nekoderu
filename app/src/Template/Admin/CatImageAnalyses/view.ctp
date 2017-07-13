<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Cat Image Analysis'), ['action' => 'edit', $catImageAnalysis->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Cat Image Analysis'), ['action' => 'delete', $catImageAnalysis->id], ['confirm' => __('Are you sure you want to delete # {0}?', $catImageAnalysis->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Cat Image Analyses'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Cat Image Analysis'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Cat Images'), ['controller' => 'CatImages', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Cat Image'), ['controller' => 'CatImages', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="catImageAnalyses view large-9 medium-8 columns content">
    <h3><?= h($catImageAnalysis->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Cat Image') ?></th>
            <td><?= $catImageAnalysis->has('cat_image') ? $this->Html->link($catImageAnalysis->cat_image->id, ['controller' => 'CatImages', 'action' => 'view', $catImageAnalysis->cat_image->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Analyzer') ?></th>
            <td><?= h($catImageAnalysis->analyzer) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($catImageAnalysis->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($catImageAnalysis->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($catImageAnalysis->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Data') ?></h4>
        <?= $this->Text->autoParagraph(h($catImageAnalysis->data)); ?>
    </div>
</div>
