<script src="https://unpkg.com/imagesloaded@4.1/imagesloaded.pkgd.min.js"></script>
<script src="//unpkg.com/masonry-layout@4.1/dist/masonry.pkgd.min.js"></script>

<!-- The Modal -->
<div id="modal-status" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="container clearfix">
            <span style="text-align:right" class="close">閉じる</span>
        </div>
        <h3 style="margin-top:10px;margin-bottom:20px;"><?= $cat->response_status->title ?></h3>
        <div><?= $cat->response_status->description ?></div>
    </div>
</div>

<!-- The Modal -->
<div id="modal-report" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="container clearfix">
            <span style="text-align:right" class="close">閉じる</span>
        </div>
        <h4 style="margin-top:10px;margin-bottom:20px;">危険、不適切な情報の報告</h4>
        
        <?php
            echo $this->Form->create(null, [
                'url' => 'cats/report',
                'id' => 'post',
                'onsubmit' => 'return confirm("送信してもいいですか？");',
                'enctype' => 'multipart/form-data'
            ]);
        ?>
        <!--<h6 id='report-target'>レポート対象</h6>-->
        <?php
            echo $this->Form->input('cat_id', ['type' => 'hidden', 'id' => 'report-cat-id']);
            echo $this->Form->textarea('description', [
                'id' => 'report-description', 
                'required' => true,
                'label' => false, 
                'placeholder' => 'ねこちゃんの位置が特定されてしまう写真やコメントが掲載されている、公序良俗に反する内容であるなどご報告ください。'
            ]);
        ?>
        <?php
            echo $this->Form->submit(
                '報告する', ['id' => 'report-submit-button', 'value'=>'投稿', 'label' => false]);
        ?>
        <?php echo $this->Form->end(); ?>

    </div>
</div>

<!-- The Modal -->
<div id="modal-address" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="container clearfix">
            <span style="text-align:right" class="close">閉じる</span>
        </div>
        <h3 style="margin-top:10px;margin-bottom:20px;">どうして位置が表示されているの？</h3>
        <p>
            これは迷子ねこ登録フォームからの投稿です。
        </p>
        <p>迷子情報を提供していただくため、また、今現在のねこちゃんの位置ではなく危険は無いため表示しています。</p>
    </div>
</div>

<div class="cats view large-9 medium-8 columns content">
    <!-- 投稿者本人の場合のみ-->
    <?php if($auth && $cat->user && $auth['id'] == $cat->user->id): ?>
        <div class="row user_menu">
            <a class="btn btn-info btn-sm edit-cat" href="/cats/edit/<?=$cat->id ?>">編集</a>
            <?= $this->Form->postLink(__('削除'), ['action' => 'delete', $cat->id, ],
                ['confirm' => __('本当に削除してもいいですか？', $cat->id), 'class' => 'btn btn-danger btn-sm delete-cat']) ?>
        </div>
        <hr>
    <?php endif; ?>
    <!-- 目撃情報の表示-->
    <?php if(count($cat->eyewitnesses) > 0): ?>
    <div class="row eyewitness">
        <?php foreach ($cat->eyewitnesses as $eyewitness): ?>
            <!-- 管理者、投稿者本人か情報提供者の場合のみ-->
            <?php if($auth && $cat->user && ($auth['is_superuser'] || $auth['id'] == $cat->user->id || $auth['id'] == $eyewitness->user->id)): ?>
            <div class="w3-panel w3-leftbar w3-sand">
                <div class="chat-info">
                    目撃情報：<span class="chat-id"><a href="/profiles/user/<?= h($eyewitness->user->username) ?>" >@<?= h($eyewitness->user->username) ?></a></span> - <span class="chat-time"><?= h($eyewitness->created) ?></span></div>
                <div>
                    <?= $eyewitness->content ?>
                </div>
                <div>
                <?php foreach ($eyewitness->eyewitness_images as $image): ?>
                    <?php if($image->thumbnail):?>
                        <a class='gallery' href="<?= $image->url ?>"><img src="<?= $image->thumbnail ?>"></img></a>
                    <?php else: ?>
                        <a class='gallery' href="<?= $image->url ?>"><img src="<?= $image->url ?>"></img></a>
                    <?php endif; ?>
                <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
        <script>
            $(function(){
                $('.eyewitness .chat-info').css('margin-left','0');
                $('.eyewitness .chat-info .chat-time').each(function(){
                    var t = new Date($(this).text()).toTwitterRelativeTime('ja');
                    $(this).text(t);
                });
            });
        </script>
    </div>
    <?php endif; ?>
    <div style="position: fixed; z-index: 999; bottom: 20px; right: 20px;">
        <a role="button" class=" encourage-popup btn btn-default btn-sm report-form" target=<?= $cat->id ?>>
              <span class="glyphicon glyphicon glyphicon-bullhorn" aria-hidden="true"></span>
        </a>
    </div>
	<div class="row">
	    <?php if(!empty($name)): ?>
	    <h3><?= $name ?></h3>
	    <?php endif; ?>
	    <div class="grid">
    	    <div class="grid-sizer"></div>
            <div class="gutter-sizer"></div>
            <?php foreach ($cat->cat_images as $image): ?>
                <div class="grid-item">
                    <?php if($image->thumbnail):?>
                        <a class='gallery' href="<?= $image->url ?>"><img src="<?= $image->thumbnail ?>"></img></a>
                    <?php else: ?>
                        <a class='gallery' href="<?= $image->url ?>"><img src="<?= $image->url ?>"></img></a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="row">
        <ul class="tags">
            <?php foreach ($cat->tags as $tag): ?>
            <li>#<?=$tag->tag ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    
    <?php if($cat->flg === 1): ?>
    <div class="w3-panel w3-info w3-padding">
        <div class="w3-tiny">
        <div><strong>飼っていた場所</strong> <i class="glyphicon glyphicon-question-sign address-info"></i></div>
        <?php if(!empty($cat->address) && trim($cat->address) !== false): ?>
            <div><?= $cat->address ?></div>
        <?php else: ?>
            <div>未登録</div>
        <?php endif; ?>
        </div>
        <a class="encourage-popup btn btn-sm btn-default w3-tiny" 
            style="margin-top:5px; color:black" 
            href="/eyewitnesses/add/<?=$cat->id ?>">情報を提供する</a>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <?= $this->element('partial/for_saving_cats_privacy'); ?>
        <?php
            echo $this->Form->create(null, [
                'url' => 'cats/addComment.json',
                'id' => 'addComment',
                'enctype' => 'multipart/form-data'
            ]);
        ?>
        
        <?php if ($auth): ?>
            <!--<?= $this->element('partial/multi_image_selector'); ?>-->
            <?= $this->Form->input('cat_id', ['type' => 'hidden', 'id' => 'cat_id', 'value' => $cat->id]); ?>
                <?= $this->Form->input('comment', ['id' => 'comment', 'label' => false]); ?>
                <?=$this->Form->submit('投稿', ['id' => 'js-submit-button', 'value'=>'投稿', 'label' => false]); ?>
        <?php else: ?>
            <div>
                <?= $this->Form->input('comment', [
                    'id' => 'comment', 
                    'label' => false,
                    'disabled' => true,
                    'placeholder' => 'ログインするとコメントを投稿できます'
                ]); ?>
            </div>
            <div class="view large-12 medium-12 columns content">
                <a href="/login">ログインする</a>
            </div>
        <?php endif; ?>
        <?php echo $this->Form->end(); ?>
        </div>
        <div class="row" id="comments">
            <div class="comment" hidden>
                <div class="chat-face">
    			    <img src="" width="30" height="30">
    		    </div>
    		    <div class="chat-info"><span class="chat-id">id here</span> - <span class="chat-time">time here</span></div>
    			<div class="chat-fukidashi">
    			  コメントテンプレート
    			</div>
    		    <div class="chat-menu"><a href="#" class="chat-trash"><i class="glyphicon glyphicon-trash"></i></a></div>
      	    </div>
        </div>
    </div>
</div>

<style>
.grid-sizer, .grid-item { 
    width: 48%; 
    padding: 5px;
    box-shadow: 2px 2px 2px 2px rgba(0,0,0,0.2);
    margin-bottom: 8px;
    border-radius: 5px;
}

.grid-item--width2 { 
    width: 80%; 
}

.grid-sizer {
    visibility: hidden;
}

.gutter-sizer { 
    width: 2%; 
}

#addComment {
    margin-top: 10px;
}

#addComment .text{
    display: inline-block;
    margin-bottom: 0px;
    width : calc(100% - 90px);
}

#addComment .submit{
    display: inline-block;
    float:right;
    width : 80px;
}


#photos {
    margin-left: 0;
    margin-bottom: 10px;
}

#photos .button-file {
    height: 60px;
    width: 60px;
}

#photos .button-file span {
    line-height: 60px;
}

#photos .button-file img {
    height: 60px;
    width: 60px;
}

.user_menu {
    margin-bottom: 10px;
}

.user_menu .edit-cat {
   text-align: left;
   float: left;
}

.user_menu .delete-cat {
   text-align: right;
   float: right;
}

ul.tags {
    margin: 0;
}

ul.tags li{
    display: inline-block;
    float: left;
    margin-right: 10px;
    font-size: 12px;
}

input[type="text"] {
    margin: 0;
}

.btn-default {
    background-image: none;
}

.eyewitness img {
    width: 25%;
}

.eyewitness .w3-panel {
    padding-bottom: 10px;
}

</style>

<script>
$(function(){
    setModal("modal-address", ".address-info", null);
    setModal("modal-status", "#status-info", null);
    setModal("modal-report", ".report-form", function(name, e){
        var cat_id = $(e.target).parent().attr('target')
        $('#report-cat-id').attr('value', cat_id);
        $('#report-description').attr('value', "");
        // $('#report-target').html('対象：<a target="_blank" href="/cats/view/' + cat_id + '">#' + cat_id + '</a>');
    });
});
</script>

<script>
$(function(){
    $('a.gallery').magnificPopup({
        type:'image',
        gallery:{
            enabled:true
        },
        callbacks: {
            open: function() {
            },
            close: function() {
              // Will fire when popup is closed
            }
        }
        
    });
});
</script>

<script>
var imageURLCache = {};

var updateComments = function(data){
    $("#comment").val('');
    $("#comments").empty();
    $("#addComment .submit input").prop("disabled", false);
    $("#addComment .submit input").attr('value', '投稿');
    
    $.each(data.comments, function() {
        var url;
        var cln = template.comment.clone();
        
        if(isJSON(this.comment)){
            var data = $.parseJSON(this.comment);
            var part = '<div>';
            data.media.forEach(function(url){
                part += '<img style="height:64px; margin-left:5px; margin-top:5px;" src=' + url + '></img>';
            });
            part +='</div>';
            part +='<div>'+data.comment+'</div>';
            cln.find('.chat-fukidashi').html(part);
            
        }else{
            cln.find('.chat-fukidashi').text(this.comment);
        }
        cln.find('.chat-id').html('<a href="/profiles/user/'+this.user.username+'">@'+this.user.username+'</a>');
        cln.find('.chat-time').text(new Date(this.created).toTwitterRelativeTime('ja') );
        
		taggify(cln, '.chat-fukidashi');
		linkify(cln, '.chat-fukidashi');
        
        var key = this.users_id;
        console.log(imageURLCache);
        if(key in imageURLCache){
            cln.find('.chat-face img').attr('style', 'background-image: url('+imageURLCache[key]+');');
            
            if("/tapatar/img/default.svg" === imageURLCache[key]){
                cln.find('.chat-face img').remove();
                cln.find('.chat-info, .chat-fukidashi').css('margin-left', '0');
            }
                    
        }else{
            $.get({                                                              
                url: '/profiles/thumbnail/' + key + '.json'
            }).done(function(data) {
                imageURLCache[key] = data;
                cln.find('.chat-face img').attr('style', 'background-image: url('+imageURLCache[key]+');');
                
                if("/tapatar/img/default.svg" === imageURLCache[key]){
                    cln.find('.chat-face img').remove();
                    cln.find('.chat-info, .chat-fukidashi').css('margin-left', '0');
                    cln.find('.chat-info, .chat-fukidashi').css('width', 'calc(80% + 40px)');
                }
                    
            });  
        }
        
        if(this.users_id !== "<?= $auth['id'] ?>"){
            cln.find('.chat-menu').remove();
        }else{
            var id = this.id;
            cln.find('a.chat-trash').click(function(e){
                e.preventDefault();
                (function(cln) {
                    if(confirm("本当に削除していいですか？")){
                        $.get({
                            url: '/cats/deleteComment/'+id+'.json',
                            success: function(response){
                                (function(cln) {
                                    console.log(response);
                                    cln.fadeOut(200, function(){
                                        cln.remove();
                                    });
                                })(cln);
                            },
                            error: function(response){
                                console.log(response);
                            }
                        });
                    }
                })(cln);
            });
        }
        
       $("#comments").append(cln);
       cln.show();
    });
    if($(".comment:first").length > 0){
        $(".comment:first").hide();
        $(".comment:first").slideDown(100);
        $("html,body").animate({scrollTop:$('.comment:first').offset().top});
    }
};

$(function(){
    createTemplate("#comments .comment:first", "comment");
    
    $.post({                                                                            
        url: '/cats/comments/<?= $cat->id ?>.json',                   
    }).done(function(data) {
        updateComments(data);
    });         
});

$('#addComment').submit(function(event) {                                                               
    event.preventDefault();
    
    if($("#comment").val().length <= 0)
        return;
    
    $("#addComment .submit input").prop("disabled", true);
    $("#addComment .submit input").attr('value', '投稿中');
    
    // 操作対象のフォーム要素を取得
    var $form = $(this);
    
    // 送信
    $.ajax({
        url: $form.attr('action'),
        method: $form.attr('method'),
        dataType: 'json',
        data: new FormData($('#addComment').get()[0]),
        processData: false,
        contentType: false,
        timeout: 10000,  // 単位はミリ秒
        // 通信成功時の処理
        success: function(result, textStatus, xhr) {
            console.log(result);
            $.post({                                                                            
                url: '/cats/comments/<?= $cat->id ?>.json',                   
            }).done(function(data) {
                updateComments(data);
            });     
        },
        // 通信失敗時の処理
        error: function(xhr, textStatus, error) {
            console.log(error);
        }
    });
        
    // $.post({                                                                            
    //     url: '/cats/addComment.json',                   
    //     data: { data: $(this).serialize() },                                                      
    // }).done(function(data) {
    //     $.post({                                                                            
    //         url: '/cats/comments/<?= $cat->id ?>.json',                   
    //     }).done(function(data) {
    //         updateComments(data);
    //     });         
    // });                                                                                           
 }); 
</script>
<script type="text/javascript">
    var container = document.querySelector('.grid');
    imagesLoaded(container, function () {
        var $grid = $('.grid').masonry({
            columnWidth: '.grid-sizer',
            gutter: '.gutter-sizer',
            itemSelector: '.grid-item',
            percentPosition: true
        });
        $grid.masonry();
    });
    
$(function(){
   taggify($('.tags'), 'li'); 
   linkify($('.tags'), 'li'); 
});
</script>

<link rel="stylesheet" type="text/css" href="<?php echo$this->Url->build('/', false); ?>css/modal.css">
<link rel="stylesheet" type="text/css" href="<?php echo$this->Url->build('/', false); ?>css/lightbox/magnific-popup.css"> 
<script src="<?php echo$this->Url->build('/', false); ?>js/lightbox/jquery.magnific-popup.js"></script>
<script async src="/tapatar/exif.js"></script>
<script async src="/tapatar/megapix-image.js"></script>
<script src="/js/twitter.relative.time.min.js"></script>