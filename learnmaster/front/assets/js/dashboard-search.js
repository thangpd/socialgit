/*global jQuery */
jQuery(function ($){
    "use strict";
    var lema = window.lema || {};
    lema.searchLesson = function() {
        if ($('.course-dashboard #lema-search-lessons').length > 0) {
            var $delay = 500;
            $(document).on('keyup', '#lema-search-lessons', function(){
                var $value = $(this).val().toUpperCase();
                $.each($('.lema-curriculum-chapter .lema-lesson-title a'), function(){
                       var $lesson = $(this).parents('.lema-curriculum-lesson');
                       var $rowValue = $(this).html().toUpperCase(); 
                       if($rowValue.indexOf($value) !== 0) {
                            $lesson.slideUp($delay);
                            $lesson.addClass('hide');
                       } else {
                            $lesson.slideDown($delay);
                            $lesson.removeClass('hide');
                       }
                });
            });
        }
    };

    lema.successLemaLesson = function() {
        if ($('.course-dashboard .progress-description').length > 0) {
            $(document).on('successLemaLesson', function(){
                var $success = $('.course-dashboard .lema-icon-check.active').length;
                var $total = $('.progress-description .lesson-total').html();
                $total = parseInt($total);
                var $percent = 0;
                if ($total > 0) {
                    $percent = ($success/$total)*100;
                }
                $('.lema-progress-percent').css('width', $percent+'%');
                $('.progress-description .lesson-sucess').html($success);
            });
        }
    };

    // init function
    $(document).ready(function() {
        lema.searchLesson();
        lema.successLemaLesson();
    });

});
