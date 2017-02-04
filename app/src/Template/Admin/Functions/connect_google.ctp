<?= $this->Form->create(null, [
    'url' => ['action' => 'connectGoogle']
    ]) ?>
<fieldset>
    <h4>client_secret.jsonを貼り付けてください</h4>
    <?php echo $this->Form->textarea('secret', array('cols' => 40, 'rows' => 10)); ?>
</fieldset>
<?= $this->Form->button(__('Googleに接続する')) ?>
<?= $this->Form->end() ?>

</pre>