(function($) {
    "use strict";
    var lema = window.lema || {};
    lema.courseDetail = function() {
        var heightScroll = 100;
        if ($('.course-detail-section-bar').length > 0) {
            var stick = $('.course-detail-section-bar').offset().top,
                offsetLeft = $('.course-detail-section-bar').offset().left,
                stickend = $('.course-detail-section-bar').offset().top + $('.lema-course-detail-wrapper').height()+100,
                sticWidth = $('.course-detail-section-bar').width(),
                $item = $('.course-detail-section-bar .lema-affix-bar');

            $(window).on("scroll load",function() {
                var scroll = $(window).scrollTop() + 150;
                if (scroll >= stick) {
                    $item.addClass('addfix').css({
                        width: sticWidth,
                        left: offsetLeft
                    });
                    if(scroll > stickend) {
                        $item.addClass('fix-end');
                    }
                    else {
                        $item.removeClass('fix-end');
                    }
                    $('.lema-affix-bar ul li a').each(function() {
                        var currLink = $(this);
                        var refElement = $(currLink.attr("href"));
                        if (refElement.length) {
                            if (refElement.offset().top < (scroll + heightScroll) && (refElement.offset().top + refElement.outerHeight()) >= scroll) {
                                $('.lema-affix-bar ul li').removeClass('active');
                                $(this).parents("li").addClass('active');
                            }else{
                                $(this).parents("li").removeClass('active');
                            }
                        }
                    });
                    $item.parents('.lema-course-content').addClass("has-fix-bar");
                } else {
                    $item.removeClass('addfix').css({
                        width: 'auto',
                        left: 'auto'
                    });
                     $item.parents('.lema-course-content').removeClass("has-fix-bar");
                }
            });

        }
        $(document).on('click', ".lema-affix-bar ul li a[href^='#']", function(e) {
            e.preventDefault();
            var hash = $(this).attr('href');
            if($(hash).length){
                $('html, body').animate({
                    scrollTop: $(hash).offset().top - heightScroll + 10
                }, 500, function() {
                    window.location.hash = hash;
                });
            }
        });
    };

    lema.Viewmorefunction =function(){
        $(document).on("click",".lema-view-more .show-more-content",function(e){
            e.preventDefault();
            if($(this).hasClass('active')){
                $(this).removeClass('active').parents('.lema-view-more').find(".lema-view-more-content").removeClass('show-all');
            }
            else{
                $(this).addClass('active').parents('.lema-view-more').find(".lema-view-more-content").addClass('show-all');
            }

        });
    };

    lema.adminbarScroll = function() {
        if($('#wpadminbar').length) {
            $('#wpadminbar').parent().find('.lema-affix-bar').addClass("hasAdminbar");
        }
    };

    // Lema Course Curriculum
    lema.CourseCurriculum =function(){

        $(document).on('click', '.le-curriculum-accordion .lema-heading', function(event) {
            event.preventDefault();
            var content = $(this).siblings('.lema-acc-body');
            $(this).toggleClass('open');
            content.toggle();
        });

        $(document).on('click', '.lema-curriculum .link-expandall', function(event){
            event.preventDefault();
            var text = 'Expand all';
            var list_content = $(this).parents('.lema-curriculum').find('.lema-acc-body');
            var list_heading = $(this).parents('.lema-curriculum').find('.lema-heading');

            if( $(this).text() === text ){
                $(this).text('Collapse All');
                list_content.show();
                list_heading.addClass('open');
            }else{
                $(this).text(text);
                list_content.hide();
                list_heading.removeClass('open');
            }

        });

        //    video course
        lema.CourseVideo = function () {
            if ($('#course-video').length > 0) {
                var $video = $('#course-video');
                var $objVideo = $video.get(0);
                $objVideo.controls = false;
                $video.parents('.image-course').addClass('video-paused');
                $('#video-action').on('click', function () {
                    if ($objVideo.paused) {
                        $objVideo.play();
                        $video.parents('.image-course').removeClass('video-paused');
                        $(this).html('<i class="fa fa-stop" aria-hidden="true"></i>');
                    } else {
                        $objVideo.pause();
                        $video.parents('.image-course').addClass('video-paused');
                        $(this).html('<i class="fa fa-play" aria-hidden="true"></i>');

                    }
                })
                $($objVideo).on('ended', function () {
                    $video.parents('.image-course').addClass('video-paused');
                    $('#video-action').html('<i class="fa fa-play" aria-hidden="true"></i>');
                })
            }
        }
    };

    $(document).ready(function() {
        lema.courseDetail();
        lema.Viewmorefunction();
        lema.adminbarScroll();
        lema.CourseCurriculum();
        lema.CourseVideo();
        // Course_rating
        $('.btn_view_more').on('click', function(){
            $(this).closest('.count-review-rating').toggleClass('count-review-rating_hide');
        });
    });
})(jQuery);