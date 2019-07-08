/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
;(function($, lema){
    "use strict";
    var courseList = window.courseList || {};

    // Dropdown list

    courseList.shortcode_ajax = function() {
        $(document).on('click','button[data-shortcode_ajax]',function(e){
            var btn = $(this);
            btn.html('<i class="fa fa-spin fa-spinner"></i>');
            var block = btn.parents('.lema-shortcode-block');
            var pos = block.offset().top - 100;
            e.preventDefault();
            btn.prop('disabled',true);
            var data = $(this).data();
            data.action = 'ajax_get_shortcode';
            if(data.paging !== undefined && $.isNumeric(data.paging) && data.page_url){
                lema.utils.updateQueryStringParameter(document.URL,'paging',data.paging,true);
            }
            lema.request.post(lemaConfig.ajaxurl,data,function(res){
                block.replaceWith(res.html);
                $('html, body').animate({
                    scrollTop: pos
                }, 100);
                btn.prop('disabled',false);
            })
        })
    };

    lema.shortcodes.register('lema_course_list', {
        run : function () {
            $.each(courseList,function(index,value){
                value();
            });
            lema.log("Lema courselist is running");
        }
    });
})(jQuery, lema);