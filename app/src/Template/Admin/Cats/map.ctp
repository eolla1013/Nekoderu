<div class="row">
    <div class="col-lg-12 text-center">
        <div id="tools">
            <div class="memo">
                <img src="<?php echo$this->Url->build('/img', false); ?>/cat_track.png"> のら猫がいる<span id="js-cat-count"></span>&nbsp;
            </div>
        </div>
        <div class="map-rapper">
            <div id="map" class="map"></div>
        </div>
    </div>
</div>
<?php 

    // debug($cats);
    $json_cats = json_encode($cats); 
    
?>

<script>
    function initMap() {
        // Create a map object and specify the DOM element for display.
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 32.806186, lng: 130.705834},
          scrollwheel: false,
          zoom: 10
        });
        
        var json_cats = <?=$json_cats?>;
        
        for(var i in json_cats){
            var cat = json_cats[i];
            (function(){
                var locs = cat.locate.split(",");
                
                var latLng = {lat: Number.parseFloat(locs[0]), lng: Number.parseFloat(locs[1])};
                console.log(cat);
                var marker = new google.maps.Marker({
                    position: latLng,
                    map: map,
                    // title: 'Hello World!'
                });
                var content = "";
                if(cat.cat_images.length > 0){
                    content +="<div style='text-align:left;'>"
                    if(cat.cat_images[0].thumbnail.length > 0){
                        content += "<img src='"+cat.cat_images[0].thumbnail+"' width='200px'>"
                    }else{
                        content += "<img src='"+cat.cat_images[0].url+"' width='200px'>"
                    }
                    
                    if(cat.cat_images[0].cat_image_analyses.length > 0){
                        var data = JSON.parse(cat.cat_images[0].cat_image_analyses[0].data);
                        var labels = data.responses[0].labelAnnotations;
                        // console.log(labels);
                        content += "<h6>画像解析結果</h6>";
                        content += "<div><ul>";
                        for(var ii in labels){
                            var label = labels[ii];
                            content += "<li>"+label.description+" ("+label.score+")</li>";
                        }
                        content += "</ul></div>";
                    }
                    content +="</div>"
                    
                    var infowindow = new google.maps.InfoWindow({
                        content: content
                    });
                    
                    marker.addListener('click', function() {
                        infowindow.open(map, marker);
                    });
                }
                
            })(cat);
        }
        
        // var myLatLng = {lat: -25.363, lng: 131.044};

       
    }
</script>

<!-- maps window template -->
<script type="x-tmpl-mustache" id="template-info-window">
    <div class="infowin">
        <div class="infowin_content">
            {{modified}}<br>
            <img width='24px' src='/img/cat_ears/{{ear_image}}'> 耳の状態：{{ear_status}} 
            <div>{{address}}</div>
            {{#cat_images}}
                <a href="{{url}}" class="lightbox"><img class="lightbox" src="{{url}}" width="30%" alt=""></a>
            {{/cat_images}}
            {{#has_comment}}
                <h6>コメント</h6>
                {{#comments}}
                    <div>{{comment}}</div>
                {{/comments}}
                <div><a href="/cats/view/{{id}}">コメントをもっと見る</a></div>
            {{/has_comment}}
            {{^has_comment}}
                <div><a href="/cats/view/{{id}}">コメントする</a></div>
            {{/has_comment}}
        </div>
    </div>
</script>


<link rel="stylesheet" href="/css/map.css">
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="<?php echo$this->Url->build('/', false); ?>js/jquery.ui.touch-punch.min.js"></script>
<!--<script type="text/javascript" src="<?php echo$this->Url->build('/', false); ?>js/index.js"></script>-->
<script async defer src="//maps.googleapis.com/maps/api/js?key=AIzaSyAb1SFRkz9TtARWL_sPqw6D3oHCgbpLLcw&callback=initMap"></script>
