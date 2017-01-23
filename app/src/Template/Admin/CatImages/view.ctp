<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Cat Image'), ['action' => 'edit', $catImage->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Cat Image'), ['action' => 'delete', $catImage->id], ['confirm' => __('Are you sure you want to delete # {0}?', $catImage->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Cat Images'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Cat Image'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Cats'), ['controller' => 'Cats', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Cat'), ['controller' => 'Cats', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="catImages view large-9 medium-8 columns content">
    <h3><?= h($catImage->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Cat') ?></th>
            <td><?= $catImage->has('cat') ? $this->Html->link($catImage->cat->id, ['controller' => 'Cats', 'action' => 'view', $catImage->cat->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($catImage->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Users Id') ?></th>
            <td><?= $this->Number->format($catImage->users_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($catImage->created) ?></td>
        </tr>
        <tr>
            <th><?= __('Modified') ?></th>
            <td><?= h($catImage->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <img src="<?= $catImage->url ?>" width="200px"></img>
    </div>
    <?php if($catImage->catImageAnalyses): ?>
        <div class="row">
            <h4><?= __('Analysis') ?></h4>
            <pre><?= $catImage->catImageAnalyses[0]->data ?>" width="200px"></pre>
        </div>
    <?php endif; ?>
    <div class="row">
        <h4><?= __('Url') ?></h4>
        <?= $this->Text->autoParagraph(h($catImage->url)); ?>
    </div>
</div>
