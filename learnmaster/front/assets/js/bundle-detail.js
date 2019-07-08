jQuery(function ($) {
    "use strict";

    var lema = window.lema || {};
    
    lema.clickScroll = function(menu) {
        $(menu + " div a[href^='#']").on("click", function (e) {
            e.preventDefault();
            //var navOffset = $(element).height();
            var anchor = $(this).attr('href');
            // smoothscroll effect
            $('html, body').animate({
                scrollTop: $(anchor).offset().top - 100,
            }, 1000);
            // add active
            $(menu + " div").removeClass('active');
            $(this).parent().addClass('active');
        });
    };

    $(document).ready(function () {
        lema.clickScroll('#lema-sidebar-anchor');
        var sidebar = $('#lema-sidebar-anchor');
        if (typeof sidebar.sticky === 'function' && sidebar.length > 0) {
            sidebar.sticky({topSpacing: 70, bottomSpacing: 603});
        }

        //view_more
        $('.view-more').on('click', function(){
            //$('.course_detail_hide.course_detail').removeClass('course_detail');
            var parent=$(this).parents('.course_row_content-item');
            var course_detail_item =parent.find('#course_detail');
            course_detail_item.toggleClass('course_detail');
            $(this).text(function(i, text){
                return text === "VIEW LESS" ? "VIEW MORE" : "VIEW LESS";
            })  
        });
    });

    $(window).on('resize load', function () {
        var window_width = $(this).width();
        var sidebar = $('#lema-sidebar-anchor');
        var heightfooter = $(".slz-wrapper-footer").height();

        if (typeof sidebar.sticky === 'function' && sidebar.length > 0) {
            if (window_width <= 991) {
                sidebar.unstick();
            }
            else if (window_width <= 1024){
                sidebar.sticky({topSpacing: 40, bottomSpacing: heightfooter + 230});
            } else {
                sidebar.sticky({topSpacing: 20, bottomSpacing: heightfooter + 230});
            }
        }
    });
});