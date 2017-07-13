<script src="https://unpkg.com/imagesloaded@4.1/imagesloaded.pkgd.min.js"></script>
<script src="//unpkg.com/masonry-layout@4.1/dist/masonry.pkgd.min.js"></script>

<style>
.btn-default {
    background-image: none;
}   

.img{
    display:inline-block; 
    width:45%; 
    object-fit: contain; 
    border: 1px solid black;
}
</style>
<!-- The Modal -->
<div class="row">
    <h3>次の２つの写真に写っている猫は似ていますか？</h3>
    
    <div id="for-saving-cats-privacy-panel" class="w3-panel w3-info w3-padding">
        <h4>これはなんですか？</h4>
        <div class="w3-small">
            コンピュータによる学習を用いた猫の類似度判定のためのデータを集める調査です。
            調査結果を猫の個体類似度計算のデータとして利用させていただきます。
            猫の個体類似度が計算ができると迷子ネコの捜索や生体追跡をサポートすることができます。
        </div>
    </div>
</div>

<?php
echo $this->Form->create($similarity);
?>

<div style="margin-bottom:2em; width:100%;" class="row" >
    <?php $ims = $images->toArray(); ?>
    <div style="width:90%; margin:0 auto; position:related">
        <div class="img" style="float:left;">
            <img src="<?=$ims[0]->url ?>" style="width:100%;">
            <?php echo $this->Form->input('image1_id', ['type' => 'hidden', 'value' => $ims[0]->id]); ?>
        </div>
        <div class="img" style="float:right;">
            <img src="<?=$ims[1]->url ?>" style="width:100%;">
            <?php echo $this->Form->input('image2_id', ['type' => 'hidden', 'value' => $ims[1]->id]); ?>
        </div>
        <div style="clear:both;"></div> 
    </div>
</div>

<div style="width:100%" class="row" >
    <?php echo $this->Form->input('answer', ['type' => 'hidden', 'value' => 0, 'id' => 'answer']); ?>
    <div style="width:90%; margin:0 auto; position:related">
        <button id="yes" style="display:inline-block; position:absolute; min-width:40%; left:7.5%;" class="btn btn-default btn-lg">似ている</button> 
        <button id="no" style="display:inline-block; position:absolute; min-width:40%; right:7.5%;"  class="btn btn-default btn-lg">似ていない</button> 
    </div>
</div>


<script>
$(function(){
    $("#yes").click(function(e){
        $("#answer").attr('value', 1);
        $("form").submit();
    })
    $("#no").click(function(e){
        $("#answer").attr('value', 0);
        $("form").submit();
    })
})
    
</script>

