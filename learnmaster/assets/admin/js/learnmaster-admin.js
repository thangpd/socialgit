/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */

;(function($, lema){
    lema.log('Lema admin running');
    var lemaAdmin = {
        loadSelectize : function (){
            $('#selectInstructor').selectize({
                plugins: ['remove_button'],
                delimiter: ',',
                persist: false,
                create: false,
                options: $('#selectInstructor').data('option')
            });
        },
        reloadChapterList :  function (currentId) {
            lema.ui.loading.show();
            $.pjax.reload({
                url : ajaxurl + '?action=ajax_chapter_list&course_id=' + $('#lema-chapter-list').parent().data('course_id') + '&current=' + (currentId ?  currentId : 0),
                container : '#lema-chapter-list',
                push: false,
                replace : false,
                timeout : 15000,
                after_completed : function () {
                    lema.ui.loading.hide();
                }
            });

        },
        reloadQuizList :  function (chapter_id, currentId) {
            lema.ui.loading.show();
            //var chapter_id =  $('#lema-quiz-list').parent().data('chapter_id');
            $.pjax.reload({
                url : ajaxurl + '?action=ajax_quiz_list&chapter_id=' + chapter_id + '&current=' + (currentId ?  currentId : 0),
                container : '#lema-quiz-list-' + chapter_id,
                push: false,
                replace : false,
                timeout : 15000,
                after_completed : function () {
                    lema.ui.loading.hide();
                }
            });
        },
        reloadQuestionList :  function (quiz_id, currentId) {
            lema.ui.loading.show();
            $.pjax.reload({
                url : ajaxurl + '?action=ajax_question_list&quiz_id=' + quiz_id + '&current=' + (currentId ?  currentId : 0),
                container : '#lema-question-list-' + quiz_id,
                push: false,
                replace : false,
                timeout : 15000,
                after_completed : function () {
                    lema.ui.loading.hide();
                }
            });
        },
        reloadLessonList :  function (chapter_id, currentId) {
            lema.ui.loading.show();
            $.pjax.reload({
                url : ajaxurl + '?action=ajax_lesson_list&chapter_id=' + chapter_id + '&current=' + (currentId ?  currentId : 0),
                container : '#lema-lesson-list-' + chapter_id,
                push: false,
                replace : false,
                timeout : 15000,
                after_completed : function () {
                    lema.ui.loading.hide();
                }
            });
        },
        reloadDataList :  function (chapter_id, currentId) {
            lema.ui.loading.show();
            $.pjax.reload({
                url : ajaxurl + '?action=ajax_data_list&chapter_id=' + chapter_id + '&current=' + (currentId ?  currentId : 0),
                container : '#lema-data-list-' + chapter_id,
                push: false,
                timeout : 15000,
                replace : false,
                after_completed : function () {
                    lema.ui.loading.hide();
                }
            });
        },
        updateVideoList : function (files) {
            var template = $('<div data-type="video" class="la-content-video-resource" id="">\n' +
                '    <input type="hidden" name="Lesson[video]" value=\'\'>\n' +
                '    <div class="inner">\n' +
                '        <div class="video-thumb">\n' +
                '            <video width="200" height="200"><source src="" type=""></video></video>\n' +
                '        </div>\n' +
                '        <div class="video-desc">\n' +
                '            <h4 class="file-name">Example_video_Wordpress_tutorial.mp4</h4>\n' +
                '            <span class="length"><b>Length:</b> <span class="videoLength"></span></span>\n' +
                '        </div>\n' +
                '        <span class="btn-remove remove-video"></span>\n' +
                '    </div>\n' +
                '</div>');
            for(var i in files) {
                var item = template.clone();
                var blockId = files[i].id;
                if ($('#video-' + blockId).length > 0) continue;
                item.find('input[type=hidden]').val(JSON.stringify(fileFilterAttributes(files[i])));
                item.find('.file-name').html(files[i].filename);
                item.attr('id', 'video-' + blockId);
                item.find('.videoLength').html(files[i].fileLength);
                item.find('video source').attr('src' ,files[i].url).attr('type',files[i].mime );
                $('#videos-container').append(item);
                $('.lema-button-add-video').hide();
                $('#la-content-video-resource-value').val(JSON.stringify(fileFilterAttributes(files[i])));
            }
        },
        updateAudioList : function (files) {
            var template = $('<div class="la-content-video-resource" id="">\n' +
                '    <input type="hidden" name="Lesson[audio]" value=\'\'>\n' +
                '    <div class="inner">\n' +
                '        <div class="video-thumb">\n' +
                '            <img class="imagetype" width="100" height="100" src="http://edu.solazu.local/wp-includes/images/media/audio.png" />\n' +
                '        </div>\n' +
                '        <div class="video-desc">\n' +
                '            <h4 class="file-name">Example_video_Wordpress_tutorial.mp4</h4>\n' +
                '            <span class="length"><b>Length:</b> <span class="audioLength"></span></span>\n' +
                '        </div>\n' +
                '        <span class="btn-remove remove-video"></span>\n' +
                '    </div>\n' +
                '</div>');
            for(var i in files) {
                var item = template.clone();
                var blockId = files[i].id;
                if ($('#audio-' + blockId).length > 0) continue;
                item.find('input[type=hidden]').val(JSON.stringify(fileFilterAttributes(files[i])));
                item.find('.file-name').html(files[i].filename);
                item.attr('id', 'audio-' + blockId);
                item.find('.audioLength').html(files[i].fileLength);
                //item.find('').attr('src' ,files[i].url).attr('type',files[i].mime );
                $('#audios-container').append(item);
                $('.lema-button-add-audio').hide();
            }
        },
        updateDownloadableList : function (files) {
            var template = $('<div class="col-100 file-item">\n' +
                '<input type="hidden" name="Lesson[resource_downloadable][]" value=""/>' +
                '    <div class="col col-10"><span class="downloadable-no"></span></div>\n' +
                '    <div class="col col-80"><span class="file-name"></span> </div>\n' +
                '    <div class="col col-10">\n' +
                '        <span class="btn-remove remove-downloadable-file" title="Remove"></span>\n' +
                '    </div>\n' +
                '</div>');
            for(var i in files) {
                var item = template.clone();
                var blockId = files[i].id;
                if ($('#downloadable-' + blockId).length > 0) continue;
                item.find('input[type=hidden]').val(JSON.stringify(files[i]));
                item.find('.file-name').html(files[i].filename);
                item.find('.downloadable-no').html($('#downloadable-files .file-item').length + 1);
                item.attr('id', 'downloadable-' + blockId);
                $('#downloadable-files').append(item);
            }

        },
        updateCodeList : function (files) {
            var template = $('<div class="col-100 file-item">\n' +
                '<input type="hidden" name="Lesson[resource_code][]" value=""/>' +
                '    <div class="col col-10"><span class="downloadable-no"></span></div>\n' +
                '    <div class="col col-80"><span class="file-name"></span> </div>\n' +
                '    <div class="col col-10">\n' +
                '        <span class="btn-remove remove-code-file" title="Remove"></span>\n' +
                '    </div>\n' +
                '</div>');
            for(var i in files) {
                var item = template.clone();
                var blockId = files[i].id;
                if ($('#code-' + blockId).length > 0) continue;
                item.find('input[type=hidden]').val(JSON.stringify(files[i]));
                item.find('.file-name').html(files[i].filename);
                item.find('.downloadable-no').html($('#code-files .file-item').length + 1);
                item.attr('id', 'code-' + blockId);
                $('#code-files').append(item);
            }

        },
        selectMediaFile : function (options) {
            var mediaUploader;
            if(mediaUploader){
                mediaUploader.open();
                return;
            }
            if (options.type) {
                options.library = {
                    type: options.type.split(',')
                };
            }
            mediaUploader = wp.media.frames.file_frame = wp.media($.extend({
                title: 'Choose a file',
                button: {
                    text: 'Choose this file'
                },
                multiple: false
            }, options));
            var self = this;

            mediaUploader.on('select', function(e){
                var callback = self[options.callback];
                var files = [];
                //Multiple
                if (options.multiple) {
                    files = mediaUploader.state().get('selection').toJSON();
                }else {
                    var file = mediaUploader.state().get('selection').first().toJSON();
                    files.push(file);
                }
                lema.log(files);
                if (callback) {
                    callback(files);
                    $('.lema-save-button').prop('disabled', false);
                }
            });
            mediaUploader.open();
        },
    };

    function fileFilterAttributes(file) {
        var allowed = ['id', 'title', 'filename', 'url', 'link', 'alt', 'author', 'description', 'caption', 'name', 'status', 'date', 'modified', 'mime', 'type', 'type', 'icon', 'meta', 'authorName', 'filesizeInBytes', 'filesizeHumanReadable', 'width' , 'height', 'fileLength', 'image', 'thumb'];
        var obj = {};
        for (key in file) {
            if (allowed.indexOf(key) >= 0) {
                obj[key] = file[key];
            }
        }
        return obj;
    }

    $(document).ready(function () {
        lemaAdmin.loadSelectize();
        if ($('#lema-chapter-list').length > 0) {
            lemaAdmin.reloadChapterList();
        }
        $(document).on('click', '.add-media-button', function(e){
            e.preventDefault();
            lemaAdmin.selectMediaFile($(this).data());
        });

        $(document).on('change', '.radiogroup', function(){
            if ($(this).is(':checked')) {
                var self = this;
                $('.radiogroup').each(function(i, e){
                   $(e).prop('checked', false);
                });
                $(this).prop('checked', true);
            }
        });

        $(document).on('click', 'div[data-selector]', function () {
            var data = $(this).data();
            $(data.target).val(data.value);
        });
        $(document).on('click', '.remove-video', function(e) {
            if (confirm('Are you sure that you want to delete this item?')) {
                var video = $(this).closest('.la-content-video-resource');
                var data = $(video).data();                
                if (video.length) {
                    $(this).parents('.la-popup-wrap').find('.lema-save-button').prop('disabled', false);
                    video.remove();
                    if (data.type) {
                        $('.lema-button-add-'+data.type).show();
                        $('#la-content-'+data.typ+'-resource-value').val('');
                    }
                }
            }
        });

        $(document).on('click', '.remove-downloadable-file, .remove-code-file', function(e) {
            var file = $(this).closest('.file-item');
            if (file.length) {
                file.remove();
            }
        });


        $(document).on('click','.la-button-remove', function(event) {
            event.preventDefault();
            /* Act on the event */
            var data = $(this).data();
            $('.la-alert-dialog .button-yes').off('click').on('click', function(event) {
                lema.ui.loading.show();
                lema.request.post(ajaxurl,data,function(res){
                    $('#la-modal').removeClass('open');
                    $('.confirm-dialog').removeClass('open');
                    $('.la-popup-backdrop').remove();
                    switch (data.target) {
                        case 'chapter-list' :
                            lemaAdmin.reloadChapterList();
                            break;
                        case 'quiz-list' :
                            lemaAdmin.reloadQuizList(data.chapter_id);
                            break;
                        case 'question-list' :
                            lemaAdmin.reloadQuestionList(data.quiz_id);
                            break;
                        case 'lesson-list' :
                            lemaAdmin.reloadLessonList(data.chapter_id);
                            break;
                        case 'data-list' :
                            lemaAdmin.reloadDataList(data.chapter_id);
                            break;
                    }
                    lema.ui.loading.hide();
                })
            });
            $('.la-alert-dialog').addClass('open');
            $('body').append('<div class="la-popup-backdrop"></div>');
        });
        $(document).on('submit', '.pjaxform', function (event) {
            return submitPjax(event, $(this));
        });
        $(document).on('pjaxSubmit','.pjaxform', function(event) {
            return submitPjax(event, $(this));
        });

        function submitPjax(event, form) {
            event.preventDefault();
            event.stopPropagation();
            var data = form.serialize();
            //$(this).find('input[type=submit]').val('Please wait...').prop('disabled');
            var _data = form.data();
            var dataTarget =_data.target;
            $.pjax.submit(event, form.data('container'), {
                timeout : 15000,
                push : false,
                contentType : 'application/x-www-form-urlencoded; charset=UTF-8',
                data : data,
                after_completed : function(){
                    switch (dataTarget) {
                        case 'chapter-list' :
                            lemaAdmin.reloadChapterList();
                            break;
                        case 'quiz-list' :
                            lemaAdmin.reloadQuizList(_data.chapter_id);
                            break;
                        case 'question-list' :
                            lemaAdmin.reloadQuestionList(_data.quiz_id);
                            break;
                        case 'lesson-list' :
                            lemaAdmin.reloadLessonList(_data.chapter_id);
                            break;
                        case 'data-list' :
                            lemaAdmin.reloadDataList(_data.chapter_id);
                            break;
                    }

                    $('#la-modal').removeClass('open');
                    $('.la-popup-backdrop').remove();
                }
            });
            return false;
        }

        lema.admin = lemaAdmin;
    });
})(jQuery, lema);