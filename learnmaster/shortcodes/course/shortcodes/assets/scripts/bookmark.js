/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/31/17.
 */
;(function($, lema){
    var bookmark = window.bookmark || {};
    
    bookmark.processCourseBookmark = function() {
        // $(document).ready(function(){
        //         $(document).on('click', '.lema-course-bookmark', (function(i, e) {
        //             var url = lema.config.ajaxurl + '?action=ajax_check_bookmark&post_id=' + $(e).attr('post_id');
        //             var i = $(e).find('i').first();
        //             i.hide();
        //             if (lema.config.user_logged_in) {
        //                 $.ajax({
        //                     url : url,
        //                     dataType : 'JSON',
        //                     success : function (response) {
        //                         i.show();
        //                         if (response) {
        //                             switch (response.status) {
        //                                 case 0 :
        //                                     $(e).remove();
        //                                     break;
        //                                 case 1 :
        //                                     i.removeClass('fa-bookmark').addClass('fa-bookmark-o');
        //                                     break;
        //                                 case 2 :
        //                                     $(e).find('i').first().removeClass('fa-bookmark-o').addClass('fa-bookmark');
        //                                     break;
        //                             }
        //                         }
        //                     }
        //                 })
        //             }

        //         });


        // });
        $(document).on('click', '.lema-course-bookmark', function(){
            $bookmark = $(this);
            $bookmark.prop('disabled',true);
            var post_id = $bookmark.data('post_id');
            var action = 'ajax_add_course_bookmark';

            var i = $bookmark.find('i');
            var iClass = i.attr('class');
            if ( i.hasClass('fa-heart') ) {
                //remove bookmark
                action = 'ajax_remove_course_bookmark';
                 
            }

            i.attr('class','fa fa-spinner fa-spin');

            var data = {
                action: action,
                post_id:post_id,
            };
            lema.request.post(lema.config.ajaxurl, data, function(res){
                if (res == 1) {
                    var cl = (iClass == 'fa fa-heart') ? 'fa fa-heart-o' : 'fa fa-heart';
                    i.attr('class',cl);
                }
                $bookmark.prop('disabled',false);
            });
         });
           
    }

    lema.shortcodes.register('lema_coursecard_bookmark', {
        run : function () {
            bookmark.processCourseBookmark();
        },
    });
})(jQuery, lema);
