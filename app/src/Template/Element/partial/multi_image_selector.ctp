<style>
#photos {
    margin-top: 5px;
    margin-left: 5%;
    width: 90%;
}

#photos .button-file {
    border-width: 1px;
    border-color: black;
    border-style: dashed;
    height: 90px;
    width: 90px;
    float: left;
    padding: 0px;
    margin-left: 3px;
}

#photos .button-file span {
    line-height: 90px;
    vertical-align: middle;
    text-shadow: none;
}

#photos .button-file img {
    height: 90px;
    width: 90px;
}

#photos label.button-file {
    text-align: center;
}


#photos .form-group {
    margin-bottom: 0px;
}
</style>

<div id="photos" class="clearfix">
    <label class="button-file">
    <?php
        echo $this->Form->input('', ['type' => 'file', 'id' => 'image_0', 'class' => 'hide', 'label' => false]);
    ?>
    <span>追加する</span>
    </label>
</div>

<script>
function initialize_images(){
    
    console.log("image-initialize");
    
(function(){
    //複数の画像ファイルアップロードに関する処理
    var numFile = 0;
    addListener(document.getElementById('image_'+numFile));
    
    function addListener(imageElem){
        imageElem.onchange = function () {
            
            console.log(imageElem.parentElement.firstChild.nodeName);
            var imgElem;
            
            if(imageElem.parentElement.firstChild.nodeName != "IMG"){
                
                imgElem = document.createElement("img");
                // imgElem.setAttribute("id", "preview");
                imgElem.setAttribute("class", "preview");
                imageElem.parentElement.parentElement.firstElementChild.firstElementChild.setAttribute("name", "image[]");
                
                numFile++;
                
                var formParentElem = imageElem.parentElement.parentElement.cloneNode(true);
                var formElem = formParentElem.firstElementChild.firstElementChild;
                formElem.setAttribute("id", "image_"+numFile);
                formElem.setAttribute("name", "");
                addListener(formElem);
                
                imageElem.parentElement.insertBefore(imgElem, imageElem);
                imageElem.parentElement.parentElement.parentElement.appendChild(formParentElem);
                
                imageElem.parentElement.parentElement.removeChild(imageElem.parentElement.parentElement.children.item(1));
            }else{
                imgElem = imageElem.parentElement.firstChild;
            }
            
            var f = this.files[0];
            
            var reader = new FileReader();
            reader.onload = (function(theFile) {
              return function(e) {
                //if EXIF and MegaPixImage exists modify orientation based on EXIF information
                if(typeof EXIF !== 'undefined' && typeof MegaPixImage !== 'undefined'){
                    EXIF.getData(theFile, function(){
                        var orientation = theFile.exifdata.Orientation;
                        var img = new Image();
                        new MegaPixImage(theFile).render(img, { orientation: orientation });
                        
                        (function(){
                            if (img.src){
                                imgElem.setAttribute("src", img.src);
                            }else{
                                setTimeout(arguments.callee, 100);
                            }
                        })();
                    });
                }else{
                    imgElem.setAttribute("src", e.target.result);
                }
              };
            })(f);
            // read the image file as a data URL.
            reader.readAsDataURL(f);
        };
    }
})();

}
</script>