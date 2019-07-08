jQuery(function($) {
    "use strict";
    var lema = window.lema || {};

    // General JS learning page
    lema.GeneralFunction = function() {
        if ($(document).find('#btn_fullscreen').length > 0) {
            document.getElementById('btn_fullscreen').addEventListener('click', function() {
                toggleFullscreen();
            });
        }

        function toggleFullscreen(elem) {
            elem = elem || document.documentElement;
            if (!document.fullscreenElement && !document.mozFullScreenElement &&
                !document.webkitFullscreenElement && !document.msFullscreenElement) {
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.msRequestFullscreen) {
                    elem.msRequestFullscreen();
                } else if (elem.mozRequestFullScreen) {
                    elem.mozRequestFullScreen();
                } else if (elem.webkitRequestFullscreen) {
                    elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                }
            }
        }

        // show title on video 

        $(document).on('mousemove', '.lema-video-wrapper', function(event) {
            event.preventDefault();
            /* Act on the event */
            if($(this).parents('.lema-curriculum-page ').hasClass('lema-navbar-open')==false){
                // $(this).addClass('hovering');
                // setTimeout(function(){
                //   $(this).removeClass('hovering');
                //   // alert("a");
                // },4000);
                $(this).addClass("hovering").delay(4000).queue(function(){
                    $(this).removeClass("hovering").dequeue();
                });
            }
        })
        $(document).on('mousestop', '.lema-video-wrapper', function(event) {
            event.preventDefault();
            /* Act on the event */
             setTimeout(function(){
                  $(this).removeClass('hovering');
                },4000);
        });;
        if($("#wpadminbar").length){
            $("body").addClass('admin-bar');
        }
    }

    // controlbar
    lema.lemaCurriculumNavbar = function() {

        // Collapse Chapter click action
        $(document).on('click', '.lema-curriculum-list .lema-chapter-heading', function(event) {
            event.preventDefault();
            var $item = $(this);
            $item.parent('.lema-curriculum-list .lema-curriculum-chapter').toggleClass('lema-collapsed');
        });

        // check lesson option
        $(document).on('click', '.lema-curriculum-lesson.quiz .lema-icon-check', function(e){
            e.preventDefault();
            alert("You can't check success for Quiz, please complete it !")
        })
        $(document).on('click', '.lema-curriculum-list .lema-curriculum-lesson:not(.quiz) .lema-icon-check', function(event) {
            event.preventDefault();
            var success = "1";
            if ($(this).hasClass('active')) {
                success = "0";
            }
            var post_id = $(this).data('post_id');
            lema.successLesson(post_id, success);
        });
        $(document).on('click','#btn-next-chap',function(e){
            e.preventDefault();
            var btn = $(this)
            btn.prop('disabled', true);
            btn.find('i').attr('class', 'fa fa-spin fa-spinner');

            var active_div = '.learning [ajax_content]';
            if($(active_div).length){
                var next = $(active_div).parent('.learning').next().find('[ajax_content]:first');
                if(next.length){
                    next.click();
                    var post_id = getPostRequest();
                    lema.successLesson(post_id);
                }else{
                    var post_id = getPostRequest();
                    lema.successLesson(post_id);
                    var data = {
                        'action' : 'complete_course',
                        'post_id' : $(document).find('input[name="lema-courseId"]').val(),
                    };
                    lema.request.post(lemaConfig.ajaxurl, data, function (res){
                        //open modal
                        $('#ajax_content').html(res.html);
                        btn.find('i').attr('class', 'fa fa-check')
                    })
                }
            }else{
                $('[ajax_content]')[0].click();
            }
        })
        $(document).on('click', '[ajax_content]', function(event) {
            event.preventDefault();
            if(! $(this).parent('.learning').length){
                $('.lema-curriculum-lesson').removeClass('learning');
                $(this).parents('.lema-curriculum-lesson').addClass('learning');
                $('[ajax_content]').prop('disabled',true);
                $('#ajax_content').html('<center class="lema-article-wrapper lema-learning-content active"><h3><i class="fa fa-spin fa-spinner"></i> Loading</h3></center>');
                if(!$(this).parents('.lema-collapsed').length){
                    $(this).parents('.lema-curriculum-chapter').toggleClass('lema-collapsed')
                }
                var post_id = $(this).attr('ajax_content');
                var data = {
                    'action' : 'get_content',
                    'id' : post_id,
                    'menu_order': $(this).index() + 1,
                    'chapter_order': $(this).parents('.lema-curriculum-chagit pter').index() + 1,
                }

                lema.utils.updateQueryStringParameter(document.URL,'lesson',post_id,true);

                //remove videojs
                if(videojs.getPlayers()['lema-video']){
                    videojs('lema-video').dispose();
                }


                lema.request.post(lemaConfig.ajaxurl,data,function(res){
                    $('#ajax_content').html(res.html);
                    $('[ajax_content]').prop('disabled',false);
                    $('.lema-curriculum-page ').removeClass('lema-navbar-open');
                    $('.lema-article-controls-bar').hide();

                    if($('.learning').hasClass('article')){
                        lema.successLesson();
                    }
                    $('#btn-next-chap').prop('disabled', false);
                    $('#btn-next-chap').find('i').attr('class', 'fa fa-step-forward');
                })
            }
        });

        $(window).on('popstate', function (e) {
            var state = e.originalEvent.state;
            if (state !== null) {
                window.location.href = document.URL;
            }
        });

        $(document).ajaxSuccess(function(){
            lema.convertSecondToTime();
            lema.playVideo();

        })
        $(document).on('submit','form[data-submit_quiz]',function(e){
            e.preventDefault();
            var btn = $(this).find('[type=submit]');
            var btnHtml = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            btn.prop('disabled', true);
            var data = $(this).serializeArray();
            data.push({'name' : 'action', 'value' : 'submit_quiz'});

            lema.request.post(lemaConfig.ajaxurl,data,function(res){
                btn.html(btnHtml);
                var result_div = $('#result_quiz');
                result_div.html(res.html);
                lema.activeModel(result_div);
                btn.prop('disabled', false);

                // check success quiz
                var post_id = data[0].value;
                if(!$('.lema-icon-check[data-post_id='+post_id+']').hasClass('active')){
                    lema.successLesson(data[0].value);
                }
            })

        })

        $(document).on('click','[data-remove_history]',function(e){
            e.preventDefault();

            var btn = $(this)
            var id = btn.data('remove_history');
            var btnHtml = btn.html();
            $('[data-remove_history]').prop('disabled',true);
            btn.html('<i class="fa fa-spinner fa-spin"></i>');

            var data = {
                'action' : 'remove_history',
                'id' : id
            }
            lema.request.post(lemaConfig.ajaxurl,data,function(res){
                if(res.code == 200){
                    btn.parents('tr').remove();
                }else{
                    alert(res.message);
                    btn.html(btnHtml);
                }
                $('[data-remove_history]').prop('disabled',false);
            })
        })
        // show/hide curriculum bar
        $(document).on('click', '.lema-curriculum-sidebar-control-st .btn-sidebar', function(event) {
            event.preventDefault();
            var $item = $(this);
            if ($item.parents('.lema-curriculum-page').hasClass('lema-navbar-open')) {
                $item.parents('.lema-curriculum-page').removeClass('lema-navbar-open');
                $('.lema-article-controls-bar').hide();
            } else {
                $item.parents('.lema-curriculum-page').addClass('lema-navbar-open');
                $('.lema-article-controls-bar').show();
            }
        });
    };
    lema.successLesson = function(post_id, success="1"){
        if (post_id==undefined) {
            post_id=getPostRequest();
        }
        var btn = ( post_id == undefined ) ? $('.learning .lema-icon-check:not(.active)') : $('.lema-icon-check[data-post_id="'+post_id+'"]');
        btn.prop('disabled',true);
        var courseId = $(document).find('input[name="lema-courseId"]').val();
        var data = {
            'action' : 'success_lesson',
            'post_id' : post_id,
            'course_id': courseId,
            'success' : success,
        }

        lema.request.post(lemaConfig.ajaxurl,data,function(res){
            btn.prop('disabled',false);
            if(res.message !== ''){
                alert(res.message);
            }else{
                if (success == "1") {
                    btn.addClass('active');
                } else {
                    btn.removeClass('active');
                }
                var curr = btn.parents('.lema-curriculum-chapter').find('.curr');

                if(success == '0'){
                    curr.text( parseInt(curr.text()) - 1);
                }else{
                    curr.text( parseInt(curr.text()) + 1);
                }
            }
            // create trigger event
            $(document).trigger('successLemaLesson');
        })
    }
    lema.hmsToSecondsOnly = function(str){
        var p = str.split(':'),
            s = 0, m = 1;

        while (p.length > 0) {
            s += m * parseInt(p.pop(), 10);
            m *= 60;
        }

        return s;
    }
    lema.playVideo = function(){
        if($('#lema-video').length){
            var data = {"controls": true, "autoplay": false, "preload": "auto","controlBar": { "muteToggle": false },"html5" : { "nativeTextTracks" : false }, "playbackRates": [0.5, 1, 1.5, 2]}
            var player = videojs('lema-video', data, function(){
                this.on('play', function() {
                    // var test=$(this);
                    // if (test.parents('.lema-curriculum-page').hasClass('lema-navbar-open')){
                    //     $('.lema-article-controls-bar').show();
                    // } else{
                    //     $('.lema-article-controls-bar').hide();
                    // }
                     // alert(player.userActive());

                });
                this.on('pause', function() {
                     // alert(player.userActive());
                    //convert time to seconds:
                    var seconds = lema.hmsToSecondsOnly($('.learning .duration').text());
                    if(this.currentTime() / seconds *100 > lemaConfig.configs.autoSuccessVideo){
                        lema.successLesson();
                    }

                });

            });
        }
    }

    lema.activeModel = function(element){
        $('.lema-lecture-view .lema-learning-content').removeClass('active');
        element.addClass("active");
    }

    lema.mainCurriculumContent = function(){

        // start quiz
        $(document).on('click', 'button[data-type],a[data-type]', function(event) {
            event.preventDefault();
            /* Act on the event */
            var data = $(this).attr('data-type');
            if(data == 'quiz-test'){
                $('form[data-submit_quiz]').find('input:radio').prop('checked',false)
                lema.countdown( parseInt( $('#countdown').data('time') ), $('.count-time') );
                lema.request.post(lemaConfig.ajaxurl,{'action' : 'set_time_start_quiz'},function(res){

                });
            }
            var element = $('.lema-lecture-view .lema-learning-content[data-type="'+data+'"]');
            lema.activeModel(element);
        });
    }
    lema.convertSecondToTime = function(){
        $.each($('.convertToTime'),function(index,value){
            var timeStr = '';
            var seconds = $(this).text();
            if ( $.isNumeric(seconds) && seconds >= 0) {
                var hours = Math.floor(seconds / 3600);
                var minutes = Math.floor( (seconds-(hours*3600)) / 60);
                seconds -= (hours*3600) + (minutes*60);
                if( hours < 10 ){
                    timeStr = "0" + hours;
                }else{
                    timeStr = hours;
                }
                if( minutes < 10 ){
                    timeStr = timeStr + ":0" + minutes;
                }else{
                    timeStr = timeStr + ":" + minutes;
                }
                if( seconds < 10){
                    timeStr = timeStr + ":0" + seconds;
                }else{
                    timeStr = timeStr + ":" + seconds;
                }
                //document.getElementById("countdowntimertxt").innerHTML = timeStr;
                $(this).html(timeStr);
            }
        })
    }
    var lema_count_down;

    lema.countdown = function (countDown,element){
        clearInterval(lema_count_down);
        var sTime = new Date().getTime();
        function UpdateCountDownTime() {
            var cTime = new Date().getTime();
            var diff = cTime - sTime;
            var timeStr = '';
            var seconds = countDown - Math.floor(diff / 1000);
            if (seconds >= 0) {
                var hours = Math.floor(seconds / 3600);
                var minutes = Math.floor( (seconds-(hours*3600)) / 60);
                seconds -= (hours*3600) + (minutes*60);
                if( hours < 10 ){
                    timeStr = "0" + hours;
                }else{
                    timeStr = hours;
                }
                if( minutes < 10 ){
                    timeStr = timeStr + ":0" + minutes;
                }else{
                    timeStr = timeStr + ":" + minutes;
                }
                if( seconds < 10){
                    timeStr = timeStr + ":0" + seconds;
                }else{
                    timeStr = timeStr + ":" + seconds;
                }
                //document.getElementById("countdowntimertxt").innerHTML = timeStr;
                element.html(timeStr)
            }else{
                element.html('<b style="color:#D53939">Time out</b>')
                //success
                clearInterval(window.counter);
            }
        }
        UpdateCountDownTime();
        lema_count_down = setInterval(UpdateCountDownTime, 500);
    }
    // init function
    function getPostRequest() {
        var post_id = '';
        var path = window.location.href;
        var arr = path.match(/lesson=([0-9]*)/);
        if (arr.length > 0) {
            post_id = arr[1];
        }
        return post_id;
    }
    $(document).ready(function() {
        lema.playVideo();
        lema.convertSecondToTime();
        lema.lemaCurriculumNavbar();
        lema.GeneralFunction();
        lema.mainCurriculumContent();
    });
});