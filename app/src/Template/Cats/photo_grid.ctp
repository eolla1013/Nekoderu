<script src="https://unpkg.com/imagesloaded@4.1/imagesloaded.pkgd.min.js"></script>
<script src="//unpkg.com/masonry-layout@4.1/dist/masonry.pkgd.min.js"></script>
<script src="/js/jquery.infinitescroll.js"></script>
<style>
    .grid-sizer, .grid-item { 

        padding: 5px;
        box-shadow: 2px 2px 2px 2px rgba(0,0,0,0.2);
        margin-bottom: 8px;
        border-radius: 5px;
    }
    .grid-sizer {
        visibility: hidden;
    }
    
    .grid-item { 
        width: 31%; 
    }
    
    .grid-item--width2 { 
        width: 40%; 
    }
    
    .gutter-sizer { 
        width: 8px; 
    }
    
    
    .btn-default {
        background-image: none;
    }
    
    .grid {
        padding-bottom: 150px;
    }
    
    #add-neko{
        background-color: white;
        border-width: 1px;
        border-color: gray;
        position: fixed;
        bottom: 0;
        width: 100%;
        height: 50px;
        left: 0;
    }
    
    #add-neko a {
      position: absolute;
      top: 50%;
      left: 42%;
      transform: translate(-20%, -50%);
    }
    
    .mfp-iframe-holder .mfp-content{
        height: 100%;
    }
    
    li.disabled{
        list-style: none;
    }
    
    /*.disabled a{*/
    /*    color:black;*/
    /*}*/
    
    .more-images{
        float: right;
        position: absolute;
        right: 10px;
        font-size: 2rem;
        color: rgba(255,255,255,0.8);
    }
    
    .notification-count{
        top: 50%;
        right: 50px;
        transform: translate(0, -50%);
        position: absolute;
    }
    
    .notification-count span {
        color : red;
    }
    
    .white-popup {
        position: relative;
        background: #FFF;
        padding: 20px;
        width: auto;
        margin: 20px auto;
    }

</style>
<?php 
    $ear_images = ['normal', 'donno', 'trimmed_right', 'trimmed_left'];
?>

<div class="row">
    <div class="grid">
        <div class="grid-sizer"></div>
        <div class="gutter-sizer"></div>
        <?php foreach ($images as $image): ?>
            <?php $cat = $image->cat; ?>
            <?php if(rand(0,15) < 0): ?>
                <div class="grid-item grid-item--width2">
            <?php else: ?>
                <div class="grid-item">
            <?php endif; ?>
                    <div>
                    <?php if($image->thumbnail):?>
                        <div><a title="<a class='more' href='/cats/view/<?=$cat->id ?>'>詳しく見る</a>" class='gallery' href="<?= $image->url ?>"><img src="<?= $image->thumbnail ?>" width="100%"></img></a></div>
                    <?php else: ?>
                        <div><a title="<a class='more' href='/cats/view/<?=$cat->id ?>'>詳しく見る</a>" class='gallery' href="<?= $image->url ?>"><img src="<?= $image->url ?>" width="100%"></img></a></div>
                    <?php endif; ?>
                    </div>
                    <!-- 画像解析結果 (管理者、投稿者本人か情報提供者の場合のみ)-->
                    <?php if($auth && $auth['is_superuser']): ?>
                    <div class="analysis">
                        <h6><a href="#">画像解析結果</a></h6>
                        <?php if(count($image->cat_image_analyses) > 0): ?>
                        <ul>
                            <?php 
                                $result = json_decode($image->cat_image_analyses[0]);
                                $data = json_decode($result->data);
                                $labels = $data->responses[0]->labelAnnotations;
                                foreach($labels as $label){
                                    // debug($label);
                                    $val = "".$label->description." (".$label->score.")"; 
                            ?>
                                    <li><?= $val ?></li>
                            <?php
                                }
                            ?> 
                        </ul>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<div class='loading-selector' style='margin-top: -150px;'>
</div>
<div class='row' id="page-nav">
    <?php echo $this->Paginator->next('全て取得しました。投稿してみませんか？', ['class'=>'next']); ?>
</div>
<div id="add-neko">
    <a class="btn btn-default btn-bg" href="<?=$this->Url->build('/', false); ?>cats/add" role="button">
        <span class="glyphicon glyphicon-camera" aria-hidden="true"></span> 投稿する
    </a>
    
    <?php if($auth): ?>
    <div class="notification-count">
        <a class="btn btn-default btn-sm" href="/profiles/notifications ?>" role="button">
              <span class="glyphicon glyphicon-bell" aria-hidden="true"></span>
              <span class="count"></span>
        </a>
    </div>
    <?php endif; ?>
</div>

<?php if($auth): ?>
<div id="notification-popup" class="white-popup mfp-hide">
    <h3>お知らせ <small><a href="/profiles/notifications">すべて見る</a></small></h3>
    <?= $this->element('partial/notifications', ["limit" => 10]); ?>
</div>
<script type="text/javascript" src="/js/notification.js"></script>
<?php endif; ?>

<script type="text/javascript">
$(function(){
    $(".analysis ul").hide();
    $(".analysis a").click(function(){
        $(".analysis ul").toggle();
        $('.grid').masonry();
    });
    
    function encourage_popup(e){
        <?php if (!$auth): ?>
            e.preventDefault();
            $.magnificPopup.open({
                items: {
                    src: '/policy/encourage'
                },
                type: 'iframe',
        		alignTop: true,
        		fixedContentPos: true,
        		overflowY: 'scroll',
            }, 0);
        <?php endif; ?>
    }
    
    function initialize(){
        lightboxing();
        favoriting();
        
        $('.encourage-popup').click(function(e) {
           encourage_popup(e);
        });
        
        <?php if(!empty($suggestRegistration) && $suggestRegistration): ?>
            $('.encourage-popup').trigger('click');
            <?php $this->request->session()->write('Last.Submit.Cat.Shown', true); ?>
        <?php endif; ?>
    }
    
    function lightboxing(){
      
        $('a.gallery').magnificPopup({
            type:'image',
            gallery:{
                enabled:true
            },
            titleSrc: 'title',
            callbacks: {
                open: function() {
                    $('a.more').click(function(e){
                        $.magnificPopup.close();
                        encourage_popup(e);
                    });
                },
                close: function() {
                  // Will fire when popup is closed
                }
            }
        });
    }
    
    function favoriting(){
        //fav button
    	$(".favorite").click(function(e){
    	   // console.log("fav");
    	    e.preventDefault();
            // console.log(e.currentTarget.href);
            
            var t = $(this);
            
            $.ajax({
                type: 'POST',
                url: e.currentTarget.href+".json",
                success: function(response){
                    if(response.cat){
                        t.children('.count').text(response.cat.favorites.length);
                        t.prependClass('favorited');
                    }
                },
                error: function(response){
                    console.log(response);
                }
            });
    	}); 
    }
    
    var $container = $('.grid');
	$container.imagesLoaded(function(){
		$container.masonry({
			isAnimated: true,
// 			columnWidth: '.grid-sizer',
// 			columnWidth: 200,
            gutter: '.gutter-sizer',
            itemSelector: '.grid-item',
            percentPosition: true,
            // fitWidth: true
		});
		initialize();
	});
	
    $container.infinitescroll({
        navSelector : '.next', // ナビゲーション
        nextSelector : '.next a', // 次ページへのリンク
        itemSelector : '.grid-item', // 次ページ内で探す要素
        debug         : false,
        dataType      : 'html',
        loading: {
            selector: '.loading-selector',
            finishedMsg: '全て取得しました。もっと投稿してみませんか？',
            img: '<?=$this->Url->build('/', false); ?>img/ajax-loader.gif',
            msgText: '<em>投稿を取得しています...</em>',
        }
    }, function( newElements ) {
		var $newElems = $( newElements );
// 		console.log($newElems);
		$newElems.imagesLoaded(function(){
		    $container.masonry('appended', $newElems, true ); 
		    initialize();
		});
	});
	
    $('.popup').magnificPopup({
		type: 'ajax',
		fixedContentPos: true,
		overflowY: 'scroll' // as we know that popup content is tall we set scroll overflow by default to avoid jump
    });
});
</script>

<link rel="stylesheet" type="text/css" href="<?php echo$this->Url->build('/', false); ?>css/lightbox/magnific-popup.css"> 
<script src="<?php echo$this->Url->build('/', false); ?>js/lightbox/jquery.magnific-popup.js"></script>

<style>
    a.more:link,a.more:visited {
        color: #008CBA;
    }
</style>