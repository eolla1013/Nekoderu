<?= $this->Form->create(null, [
    'url' => ['action' => 'getTNRData']
    ]) ?>
<fieldset>
<?php
        echo $this->Form->input('sheetId');
        echo $this->Form->input('folderId');
        echo $this->Form->input('num');
?>
</fieldset>
<?= $this->Form->button(__('取得する')) ?>
<?= $this->Form->end() ?>

<?php
    if(isset($json)){
        echo $json;
    }
?>
</pre>