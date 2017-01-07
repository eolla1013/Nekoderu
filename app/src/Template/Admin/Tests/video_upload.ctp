<link rel="stylesheet" href="//code.jquery.com/ui/jquery-ui-git.css">
<script src="//code.jquery.com/ui/jquery-ui-git.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>

<style>

#video{
	position: absolute; 
	bottom: 0; 
	left: 0; 
	width: 100%; 
	height: 100%; 
	background-color: #FFFFFF; 
	color: black;
}

#playback{
	position: absolute; 
	bottom: 0; 
	left: 0; 
	width: 100%; 
	height: 100%; 
	background-color: #FFFFFF; 
	color: black;
}

.slider {
	margin-left: 10%;
	width: 80%;
	text-align: center;
}

.selectMovieButton{
	position: absolute;
	top: 0;
	bottom: 0;
	height: 100px;
	margin: auto;
	
    display:inline-block;
    overflow:hidden;
    border-radius:3px;
    background:#099;
    color:#fff;
    text-align:center;
    line-height:30px;
    width:100%;
    cursor:pointer;
}

.selectMovieButton:hover {
    background:#0aa;
}
.selectMovieButton div{
	display:inline-block;
	position: absolute;
    top: 0;
    bottom: 0;
    height: 2em;
    line-height: 2em;
    margin: auto auto;
    text-align: center;
    left: 0;
    right: 0;
}

.selectMovieButton input[type=file] {
    position:absolute;
    top:0;
    left:0;
    margin:auto;
    width:100%;
    height:100%;    
    cursor:pointer;
    opacity:0;
}

#slider-range .ui-slider-handle, #slider-range .ui-slider-range{
	background-color: #099;
}

.uploadButton {
	width: 100%;
	
    display:inline-block;
    overflow:hidden;
    border-radius:3px;
    background:#099;
    color:#fff;
    text-align:center;
    margin-top: 1em;
    line-height: 3em;
    cursor:pointer;
}
</style>

<!--<div style="margin-top:100px"></div>-->

<div style="position:relative; width:100%; height:300px">
	<video id="video" width="100%" height="100%" playsinline muted autoplay webkit-playsinline="webkit-playsinline" 
		onClick="playVideo()">
	</video>
	<button id="playback" onClick="playVideo()">ビデオが表示されないときは<br>タップしてください</button>
	<div class="selectMovieButton">
		<div>
	    	タップしてムービーを選択
	    	<input type="hidden" name="cat_id" />
		    <input id="input" name="file" type="file" />
	    </div>
	</div>
</div>

<div class="slider">
	<div>スライダを動かして<br>６秒間選択してください</div>
	<div id="slider-range"></div>
	<div><span id="selected-duration">0</span> / <span id="whole-duration">0</span><br></div>
	<div class="uploadButton">アップロードする</div>
</div>
<!--<div class="cancelButton">やり直す</div>-->

<script>
  $( function() {
    $( "#slider-range" ).slider({
      range: true,
      min: 0,
      max: 500,
      values: [ 75, 300 ],
      slide: function( event, ui ) {
        $( "#amount" ).val( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] );
      }
    });
    // $( "#amount" ).val( "" + $( "#slider-range" ).slider( "values", 0 ) +
    //   " - " + $( "#slider-range" ).slider( "values", 1 ) );
  } );
</script>

<script type="text/javascript">
var v = document.getElementById("video");
var input = document.getElementById('input');
var reader;
var url;
var current;

$("#playback").hide();
$(".slider").hide();
$("#video").hide();

$("#slider-range").on( "slide", function( event, ui ) {
	var values = $(this).slider("option", "values");
	
	var video = $('#video').get(0);
	
	if(current[0] != values[0]){
		video.currentTime = values[0];
		video.pause();
	}
	if(current[1] != values[1]){
		video.currentTime = values[1];
		video.duration = values[1];
		video.pause();
	}
	
	current[0] = values[0];
	current[1] = values[1];
	manageSlider();
});

v.addEventListener("timeupdate",  function (evt) {
	
	if(!v.paused && v.currentTime >= current[1]){
		v.pause();
		v.currentTime  = current[0];
	}
	
	console.log(evt);
});

v.addEventListener('ended', function (evt) {
	// console.log(evt);
	v.currentTime = current[0];
});

v.addEventListener('seeking', function (evt) {
	// console.log(evt);
});

v.addEventListener('seeked', function (evt) {
	// console.log(evt);
});

v.addEventListener('progress', function (evt) {
	// console.log(evt);
});

v.addEventListener('loadeddata', function (evt) {
	// console.log(evt);
	
	//再生後に呼ばれる
    //alert("loadeddata");
    $("#playback").hide();
    $(".slider").show();
    $("#video").show();
	
	reader.revokeObjectURL(url);  //free up memory
	$("#slider-range").slider("option", "min", 0);
	$("#slider-range").slider("option", "step", v.duration / 100.0);
	$("#slider-range").slider("option", "max", v.duration);
	$("#slider-range").slider( "option", "values", [ 0, v.duration ] );
	
	current = [0, v.duration];
	manageSlider();
});

v.addEventListener('loadstart', function (evt) {
	// console.log(evt);
	$("#playback").show();
	$(".selectMovieButton").hide();
	
	//ここはファイル選択後に呼ばれる
    // alert("loadstart");
});

input.addEventListener('change', function (evt) {
    // reader = new window.FileReader();
    var file = evt.target.files[0];
    
    $("#playback").hide();
    // alert("fileLoad");
    v.src = null;

	reader = window.URL || window.webKitURL;

    if (reader && reader.createObjectURL) {
        url = reader.createObjectURL(file);
        v.src = url;
        return;
    }

    if (!window.FileReader) {
        console.log('Sorry, not so much');
        return;
    }

    reader = new window.FileReader();
    reader.onload = function(evt) {
       v.src = evt.target.result;
    };
    reader.readAsDataURL(file);
}, false);

function manageSlider(){
	if(current[1] - current[0] <= 6){
		$("#slider-range .ui-slider-handle, #slider-range .ui-slider-range").css("background-color","#099");
		$(".uploadButton").show();
	}else{
		$("#slider-range .ui-slider-handle, #slider-range .ui-slider-range").css("background-color","#900");
		$(".uploadButton").hide();
	}
	
	$("#selected-duration").text(t(current[1] - current[0]));
	$("#whole-duration").text(t(v.duration));
	
	
}

function t(dur){
	return parseInt(dur * 1000) / 1000
}

function playVideo() {
	// console.log("playVideo");
	
	if(v.currentTime >= current[1]){
		v.currentTime = current[0];
	}
	
	v.play();
	
}

function pauseVideo() {
	//動画を一時停止
	v.pause();
}

function upVolume() {
	//音量を上げる
	v.volume = v.volume + 0.25;
}

function downVolume() {
	//音量を下げる
	v.volume = v.volume - 0.25;
}
</script>