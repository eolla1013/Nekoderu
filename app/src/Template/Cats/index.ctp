<div class="cats index large-12 medium-8 columns content">
    <h3><?= __('Cats') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('time') ?></th>
                <th><?= $this->Paginator->sort('locate') ?></th>
                <th><?= $this->Paginator->sort('flg') ?></th>
                <th><?= $this->Paginator->sort('status') ?></th>
                <th><?= $this->Paginator->sort('ear_shape') ?></th>
                <th><?= $this->Paginator->sort('comments') ?></th>
                <th><?= $this->Paginator->sort('user') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cats as $cat): ?>
            <tr>
                <td><?= $this->Number->format($cat->id) ?></td>
                <td><?= $this->Number->format($cat->time) ?></td>
                <td><?= h($cat->locate) ?></td>
                <td><?= $this->Number->format($cat->flg) ?></td>
                <td><?= $this->Number->format($cat->status) ?></td>
                <td><?= $this->Number->format($cat->ear_shape) ?></td>
                <td><?= $this->Number->format(count($cat->comments)) ?></td>
                <td>
                    <?php if(isset($cat->user)): ?>
                        <?= h($cat->user->username) ?>
                    <?php endif; ?>
                </td>
                <td><?= h($cat->created) ?></td>
                <td><?= h($cat->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $cat->id]) ?>
                    <!--<?= $this->Html->link(__('Edit'), ['action' => 'edit', $cat->id]) ?>-->
                    <!--<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $cat->id], ['confirm' => __('Are you sure you want to delete # {0}?', $cat->id)]) ?>-->
                </td>
            </tr>
            <tr><td colspan=11>
            <?php foreach ($cat->cat_images as $image): ?>
                <img src="<?= $image->url ?>" width="64px"></img>
            <?php endforeach; ?>
            </td></tr>
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
