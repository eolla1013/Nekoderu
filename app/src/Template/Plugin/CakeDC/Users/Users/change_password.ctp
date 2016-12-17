<?php
//デフォルトのレイアウトを外す
$this->layout= '';
//ネコデルのレイアウトを適用
$this->extend('/Layout/nekoderu');
?>

<div class="users form">
    <?= $this->Flash->render('auth') ?>
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __d('CakeDC/Users', '新しいパスワードを入力してください') ?></legend>
        <?php if ($validatePassword) : ?>
            <?= $this->Form->input('current_password', [
                    'type' => 'password',
                    'required' => true,
                    'label' => __d('CakeDC/Users', '変更前（現在）のパスワード')]);
            ?>
        <?php endif; ?>
        
        <?= $this->Form->input('password', [
            'type' => 'password', 
            'required' => true,
            'label' => __d('CakeDC/Users', '新しいパスワード')]); ?>
        <?= $this->Form->input('password_confirm', [
            'type' => 'password', 
            'required' => true,
            'label' => __d('CakeDC/Users', '新しいパスワード（確認）')]); ?>

    </fieldset>
    <?= $this->Form->button(__d('CakeDC/Users', '実行')); ?>
    <?= $this->Form->end() ?>
</div>