<div style="margin:15px 0;text-align:center">
    あなたが見かけた「ねこ」について教えて下さい
</div>
<!-- The Modal -->
<div id="modal-ear" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="container clearfix">
            <span style="text-align:right" class="close">閉じる</span>
        </div>
        <h3 style="margin-top:10px;margin-bottom:20px;">ねこの耳の状態について</h3>
        <p>避妊手術したのらねこには、耳のいち部をＶ字カット（さくら耳）する処置を行います。
        カットは麻酔が効いている間に行うので痛みはありません。</p>
        <p>ねこの耳の形がわかると手術の必要なねこであるかが分かります。</p>
    </div>
</div>

<!-- The Modal -->
<div id="modal-gps" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="container clearfix">
            <span style="text-align:right" class="close">閉じる</span>
        </div>
        <h3 style="margin-top:10px;margin-bottom:20px;">位置情報が取れませんか？</h3>
        <p>本体の設定から位置情報の利用を許可してください。</p>
        <p>位置が取得できない場合は、住所や場所のわかる内容をご記入ください。</p>
        <p>入力された住所、位置情報は一般には公開されません。</p>
    </div>
</div>
        
<?php
    echo $this->Form->create(null, [
        'url' => 'cats/add',
        'id' => 'post',
        'onsubmit' => 'return confirm("送信してもいいですか？");',
        'enctype' => 'multipart/form-data'
    ]);
    echo $this->Form->input('time', ['type' => 'hidden', 'id' => 'time']);
    echo $this->Form->input('locate', ['type' => 'hidden', 'id' => 'locate']);
?>
    <div class="box">
        <div class="memo-title">１．写真を選ぶ
            <div id="photos" class="clearfix">
                <label class="button-file">
                <?php
                    echo $this->Form->input('', ['type' => 'file', 'id' => 'image_0', 'class' => 'hide', 'label' => false]);
                ?>
                <span>追加する</span>
                </label>
            </div>
        </div>
    </div>
    <div id='now' class="box">
        <div class="memo-title">
            ２．場所を設定する <a id='get_gps' href="#">クリックしてGPSから取得</a> <i id='gps-info' class="glyphicon glyphicon-question-sign"></i>
        </div>
        <?php echo $this->Form->input('', ['type' => 'text', 'id' => 'address', 'placeholder' => '住所や場所のわかる内容を記入してください']); ?>
    </div>
    <div class="box">
        <div class="memo-title">３．ねこの耳の情報を入力する <i id='ear-info' class="glyphicon glyphicon-question-sign"></i></div>
        <div class="ears inline_checkboxes">
            <?php
                echo $this->Form->input(
                    'ear_shape',
                    array(
                        'multiple' => 'checkbox',
                        'type' => 'radio',
                        'options' => $this->Cats->earOptions(),
                        'escape' => false,
                        'label' => false,
                        'default' => '0'
                    )  
                );
             ?>
        </div>
        <div class="memo-title"><a href='#' id='input-details'>クリックしてねこの詳しい情報を入力する？</a></div>
        
        <table class="details">
        <?php foreach ($questions as $question): ?>
            <?php if ($question['type'] == 'radio'): ?>
            <tr>
                <td><label><?= $question['displayName'] ?></label></td>
            <td>
                <div class="inline_checkboxes">
                    <?php 
                        $options = explode(',', $question['options']);
                        $ar = [];
                        foreach($options as $option){
                            $ar[] = ['value' => $option, 'text' => $option];
                        }
                        echo $this->Form->input(
                            $question['name'],
                            array(
                                'multiple' => 'checkbox',
                                'type' => 'radio',
                                'options' => $options,
                                'escape' => false,
                                'label' => false,
                                'default' => '0'
                            )  
                        );
                    ?>
                </div>
            </td>
            </tr>
            <?php endif; ?>
            <?php if ($question['type'] == 'text'): ?>
            <tr>
                <td><label><?= $question['displayName'] ?></label></td>
                <td>
                    <div>
                        <?php
                            echo $this->Form->text($question['name'], ['placeholder' => $question['description'] ]);
                        ?>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        </table>
    </div>
     <div class="box">
        <div class="memo-title">４．コメントを書く</div>
        <?php
        echo $this->Form->input('comment', 
            ['type' => 'textarea', 'id' => 'comment', 'label' => false, 'rows' => 2,
            'placeholder'=>'ねこについて教えて下さい。人懐っこい？怖がり？']);
        ?>
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
$(function(){
    setModal("modal-ear", "ear-info");
    setModal("modal-gps", "gps-info");
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
</style>

<!--<div id="customZoomBtn">-->
<!--    <div id="small" class="float_l btn">ズームアウト</div>-->
<!--    <div id="big" class="float_l btn">ズームイン</div>-->
<!--</div>-->
<!--<div id="footer">Project NEKODERU</div>-->
<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>-->
<!--<script type="text/javascript" src="//maps.google.com/maps/api/js?key=AIzaSyAb1SFRkz9TtARWL_sPqw6D3oHCgbpLLcw"></script>-->
<!--<script type="text/javascript" src="<?php echo$this->Url->build('/', false); ?>js/rousui_post.js"></script>-->
<script type="text/javascript" src="<?php echo$this->Url->build('/', false); ?>js/add_neko.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo$this->Url->build('/', false); ?>css/add_neko.css"> 
<link rel="stylesheet" type="text/css" href="<?php echo$this->Url->build('/', false); ?>css/modal.css"> 


