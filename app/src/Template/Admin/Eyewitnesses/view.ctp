<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Eyewitness'), ['action' => 'edit', $eyewitness->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Eyewitness'), ['action' => 'delete', $eyewitness->id], ['confirm' => __('Are you sure you want to delete # {0}?', $eyewitness->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Eyewitnesses'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Eyewitness'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Cats'), ['controller' => 'Cats', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Cat'), ['controller' => 'Cats', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Eyewitness Images'), ['controller' => 'EyewitnessImages', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Eyewitness Image'), ['controller' => 'EyewitnessImages', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="eyewitnesses view large-9 medium-8 columns content">
    <h3><?= h($eyewitness->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $eyewitness->has('user') ? $this->Html->link($eyewitness->user->id, ['controller' => 'Users', 'action' => 'view', $eyewitness->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cat') ?></th>
            <td><?= $eyewitness->has('cat') ? $this->Html->link($eyewitness->cat->id, ['controller' => 'Cats', 'action' => 'view', $eyewitness->cat->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($eyewitness->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($eyewitness->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($eyewitness->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Content') ?></h4>
        <?= $this->Text->autoParagraph(h($eyewitness->content)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Eyewitness Images') ?></h4>
        <?php if (!empty($eyewitness->eyewitness_images)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Eyewitness Id') ?></th>
                <th scope="col"><?= __('Url') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($eyewitness->eyewitness_images as $eyewitnessImages): ?>
            <tr>
                <td><?= h($eyewitnessImages->id) ?></td>
                <td><?= h($eyewitnessImages->eyewitness_id) ?></td>
                <td><?= h($eyewitnessImages->url) ?></td>
                <td><?= h($eyewitnessImages->created) ?></td>
                <td><?= h($eyewitnessImages->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'EyewitnessImages', 'action' => 'view', $eyewitnessImages->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'EyewitnessImages', 'action' => 'edit', $eyewitnessImages->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'EyewitnessImages', 'action' => 'delete', $eyewitnessImages->id], ['confirm' => __('Are you sure you want to delete # {0}?', $eyewitnessImages->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
