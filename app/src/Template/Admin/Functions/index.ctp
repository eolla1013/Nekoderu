<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Grid'), ['controller' => 'Cats', 'action' => 'grid']) ?></li>
        <li><?= $this->Html->link(__('Map'), ['controller' => 'Cats', 'action' => 'map']) ?></li>
        
        <li><?= $this->Html->link(__('Googleに接続'), ['action' => 'connectGoogle']) ?></li>
        <li><?= $this->Html->link(__('TNRデータを読み込み'), ['action' => 'getTnrData']) ?></li>
    </ul>
</nav>