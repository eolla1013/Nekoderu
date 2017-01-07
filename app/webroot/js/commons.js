'use strict';

(function($) {

    jQuery.fn.extend({
        prependClass: function(newClasses) {
            return this.each(function() {
                var currentClasses = $(this).prop("class");
                $(this).removeClass(currentClasses).addClass(newClasses + " " + currentClasses);
            });
        }
    });

})(jQuery);

var template;   

var createTemplate = function(selector, name){
        
    if(typeof template === 'undefined'){
        template = {};
    }
    eval("template."+name+" =  $('"+selector+"').first();");
}

function setModal(id, btn, callback){
    var modal = $("#"+id);
    modal.css('z-index', '999');
   
    $(window).click(function(e) {
        // e.preventDefault();
        if (e.target.id === modal.attr('id')) {
            modal.css('display', 'none');
        }
    });
    
    $("#"+id+" .modal-content .close").click(function(e){
        // e.preventDefault();
        modal.css('display', 'none');
    });
    
     $(btn).click(function(e){
        // e.preventDefault();
        if(callback){
            callback('showing', e);
        }
        var modal = $("#"+id);
        modal.css('display', 'block');
    });
}

var isJSON = function(arg) {
    arg = (typeof arg === "function") ? arg() : arg;
    if (typeof arg  !== "string") {
        return false;
    }
    try {
    arg = (!JSON) ? eval("(" + arg + ")") : JSON.parse(arg);
        return true;
    } catch (e) {
        return false;
    }
};

var linkify = function(parent, selector){

    function _linkify(str){
        
        return str.replace(/((http|https|ftp):\/\/[\w?=&.\/-;#~%-]+(?![\w\s?&.\/;#~%"=-]*>))/g, '<a href="$1">$1</a> ');
        
    }
    
    parent.find(selector).each(function(){
        var t = $(this);
        if(!t.hasClass('linkified')){
            t.addClass('linkified');
            t.html(_linkify(t.html()));
        }
    });

}

var taggify = function(parent, selector){

    function _taggify(str){
        var hash = '#＃';
        var tag = 'A-Za-z〃々ぁ-ゖ゛-ゞァ-ヺーヽヾ一-龥Ａ-Ｚａ-ｚｦ-ﾟ';
        // var tag = 'a-zÀ-ÖØ-öø-ÿĀ-ɏɓ-ɔɖ-ɗəɛɣɨɯɲʉʋʻ̀-ͯḀ-ỿЀ-ӿԀ-ԧⷠ-ⷿꙀ-֑ꚟ-ֿׁ-ׂׄ-ׇׅא-תװ-״﬒-ﬨשׁ-זּטּ-לּמּנּ-סּףּ-פּצּ-ﭏؐ-ؚؠ-ٟٮ-ۓە-ۜ۞-۪ۨ-ۯۺ-ۼۿݐ-ݿࢠࢢ-ࢬࣤ-ࣾﭐ-ﮱﯓ-ﴽﵐ-ﶏﶒ-ﷇﷰ-ﷻﹰ-ﹴﹶ-ﻼ‌ก-ฺเ-๎ᄀ-ᇿ㄰-ㆅꥠ-꥿가-힯ힰ-퟿ﾡ-ￜァ-ヺー-ヾｦ-ﾟｰＡ-Ｚａ-ｚぁ-ゖ゙-ゞ㐀-䶿一-鿿꜀-뜿띀-렟-﨟〃々〻'; // 全言語対応
        var digit = '0-9０-９';
        var underscore = '_';
        
        var pattern = new RegExp(
          '(?:^|[^' + tag + digit + underscore + ']+)' +
          '[' + hash + ']' +
          '(' +
          '[' + tag + digit + underscore + ']*' +
          '[' + tag + ']+' +
          '[' + tag + digit + underscore + ']*' +
          ')' +
          '(?![' + hash + tag + digit + underscore + ']+)',
        'g');
        return str.replace(pattern, " <a href='/cats/tag/$1'>#$1</a> ");
    
    }
    
    parent.find(selector).each(function(){
        var t = $(this);
        if(!t.hasClass('taggified')){
            t.addClass('taggified');
            t.html(_taggify(t.text()));
        }
    });

}