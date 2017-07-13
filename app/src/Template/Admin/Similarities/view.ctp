<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Similarity'), ['action' => 'edit', $similarity->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Similarity'), ['action' => 'delete', $similarity->id], ['confirm' => __('Are you sure you want to delete # {0}?', $similarity->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Similarities'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Similarity'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="similarities view large-9 medium-8 columns content">
    <h3><?= h($similarity->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($similarity->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cat1 Id') ?></th>
            <td><?= $this->Number->format($similarity->cat1_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cat2 Id') ?></th>
            <td><?= $this->Number->format($similarity->cat2_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Answer') ?></th>
            <td><?= $similarity->answer ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
