/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/31/17.
 */
;(function($, lema){
    var coursecard = window.coursecard || {};

    coursecard.showDescription = function() {
        $(".lema-course").each(function(){

                $(".item.lema-course").hover(
                    function() {
                        $(".item.lema-course").css("z-index", "1");
                        $(this).css("z-index", "3"); }
                );
        }); 
        
    };

    lema.shortcodes.register('course_card_shortcode', {
        run : function () {
            //coursecard.showDescription();
        },
    });
})(jQuery, lema);
