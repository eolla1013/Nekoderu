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