jQuery(function($) {
    "use strict";
    var lema = window.lema || {};

    // show all comments
    lema.audioLearning =function(){
        $(document).on('click', '.lema-block-audio', function(e){
            var audio = $(this).data('target');
            var lesson = $('.audio.learning .lema-icon-check');
            var check_success = (lesson.hasClass('active')) ? false : true ;
            audio = document.getElementById(audio);
            audio.paused ? audio.play() : audio.pause();

            audio.ontimeupdate = function(e){
                // >70% auto success
                if(check_success && e.target.currentTime > (e.target.duration / 100 * 70)){
                    lema.successLesson(lesson.data('post_id'))
                    check_success = false
                }
            }
        })
    }

    $(document).ready(function() {
      lema.audioLearning();
    });

});