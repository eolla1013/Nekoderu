<div style="margin:15px 0;text-align:center">
    目撃した「迷子ねこ」の情報を教えて下さい
</div>
<div id="for-saving-cats-privacy-panel" class="w3-panel w3-note w3-padding">
    <h6>目撃情報の表示について</h6>
    <div class="w3-small">
        ここで投稿した情報は迷子ねこの登録者本人様、情報提供者様、及び、本サービス管理者以外は閲覧できません。
    </div>
</div>

<?= $this->element('partial/for_saving_cats_privacy'); ?>
<?php
    echo $this->Form->create(null, [
        'url' => '/eyewitnesses/add/'.$cat->id,
        'id' => 'post',
        'onsubmit' => 'return confirm("送信してもいいですか？");',
        'enctype' => 'multipart/form-data'
    ]);
    echo $this->Form->input('time', ['type' => 'hidden', 'id' => 'time']);
    echo $this->Form->input('locate', ['type' => 'hidden', 'id' => 'locate']);
?>
  
    <div class="box">
        <div class="memo-title">目撃情報について詳しくご記入ください <sup class="required">※必須</sup></div>
        <?php
        echo $this->Form->input('comment', 
            ['type' => 'textarea', 'id' => 'comment', 'label' => false, 'rows' => 3,
            'placeholder'=>'どこで見ましたか？どんな様子でしたか？首輪など目印がありますか？']);
        ?>
    </div>
     <div class="box">
        <div class="memo-title">
            写真があれば添付してください
            <?= $this->element('partial/multi_image_selector'); ?>
        </div>
    </div>

    <div class="btn-default">
        <?php
        echo $this->Form->submit(
            '投稿', ['id' => 'js-submit-button', 'value'=>'投稿', 'label' => false]);
    ?>
    </div>
<?php echo $this->Form->end(); ?>

<!--<div class="map-rapper">-->
<!--    <div id="map" class="map"></div>-->
<!--</div>-->
<script>

$("form").submit(function(e) {

    var ref = $(this).find("[required]");
    $(ref).each(function(){
        if ( $(this).val() == '' ) {
            alert("場所の情報を入力してください");

            $(this).focus();
            e.preventDefault();
            return false;
        }
    });  return true;
});

</script>

<style>
    
input[type=radio] {
    float:left;
    margin:0px;
    width:20px;
    }

form#changeRegionStdForm input[type=radio].locRad {
    margin:3px 0px 0px 3px; 
    float:none;
}

.required{
    color:red;
}
</style>

<script type="text/javascript" src="<?php echo$this->Url->build('/', false); ?>js/add_neko.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo$this->Url->build('/', false); ?>css/add_neko.css"> 
<link rel="stylesheet" type="text/css" href="<?php echo$this->Url->build('/', false); ?>css/modal.css"> 


