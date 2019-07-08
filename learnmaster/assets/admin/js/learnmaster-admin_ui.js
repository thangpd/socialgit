;(function ($) {
    'use strict';
    // function countdown number text input
    var admin_ui = {
        init: function () {
            $.each(this, function (i, fc) {
                fc();
            })
        },

        GeneralFunction: function () {
            //attach video render
            $('.fm-attach-video').each(function () {
                var frame,
                    metaBox = $(this), // Your meta box id here
                    addVideoLink = metaBox.find('.upload_custom_video'),
                    delVideoLink = metaBox.find('.delete_custom_video'),
                    videoContainer = metaBox.find('.custom_video_container'),
                    videoIdInput = metaBox.find('input[type="hidden"]');
                // ADD IMAGE LINK
                addVideoLink.on('click', function (event) {
                    event.preventDefault();
                    if (frame) {
                        frame.open();
                        return;
                    }
                    frame = wp.media({
                        title: 'Select Video',
                        button: {
                            text: 'Use this media'
                        },
                        multiple: false,
                        type: 'video'
                    });
                    frame.on('select', function () {
                        var attachment = frame.state().get('selection').first().toJSON();
                        if (attachment.type == 'video') {
                            videoContainer.append('<video width="320" height="240" controls> <source src="' + attachment.url + '" type="video/mp4"></video>');
                            videoIdInput.val(attachment.id);
                            addVideoLink.addClass('hidden');
                            delVideoLink.removeClass('hidden');
                        }
                    });
                    frame.open();
                });
                // DELETE IMAGE LINK
                delVideoLink.on('click', function (event) {
                    event.preventDefault();
                    videoContainer.html('');
                    addVideoLink.removeClass('hidden');
                    delVideoLink.addClass('hidden');
                    videoIdInput.val('');
                });
                if (videoIdInput.val() != '') {
                    var src = videoIdInput.attr('data-src');
                    if (src != '' && src != undefined) {
                        videoContainer.append('<video width="320" height="240" controls> <source src="' + src + '" type="video/mp4"></video>');
                        addVideoLink.addClass('hidden');
                        delVideoLink.removeClass('hidden');
                    }
                }
            });

            //attach image
            $('.fm-attach-image').each(function () {
                var frame,
                    metaBox = $(this), // Your meta box id here
                    addImageLink = metaBox.find('.upload_custom_image'),
                    delImageLink = metaBox.find('.delete_custom_image'),
                    imageContainer = metaBox.find('.custom_image_container'),
                    imageIdInput = metaBox.find('input[type="hidden"]');
                // ADD IMAGE LINK
                addImageLink.on('click', function (event) {
                    event.preventDefault();
                    if (frame) {
                        frame.open();
                        return;
                    }
                    frame = wp.media({
                        title: 'Select Image',
                        button: {
                            text: 'Use this media'
                        },
                        multiple: false,
                        type: 'image'
                    });
                    frame.on('select', function () {
                        var attachment = frame.state().get('selection').first().toJSON();
                        if (attachment.type == 'image') {
                            imageContainer.append('<img width="320" height="auto" src="' + attachment.url + '" alt="">');
                            imageIdInput.val(attachment.id);
                            addImageLink.addClass('hidden');
                            delImageLink.removeClass('hidden');
                        }
                    });
                    frame.open();
                });
                // DELETE IMAGE LINK
                delImageLink.on('click', function (event) {
                    event.preventDefault();
                    imageContainer.html('');
                    addImageLink.removeClass('hidden');
                    delImageLink.addClass('hidden');
                    imageIdInput.val('');
                });
                if (imageIdInput.val() != '') {
                    var src = imageIdInput.attr('data-src');
                    if (src != '' && src != undefined) {
                        imageContainer.append('<img width="320" height="auto" src="' + src + '" alt="">');
                        addImageLink.addClass('hidden');
                        delImageLink.removeClass('hidden');
                    }
                }

            });

            $('la-popup.modal-chapter').each(function (index, el) {
                $(".la-popup .button-cancel").on('click', function (event) {
                    event.preventDefault();
                    $(this).parents('.la-popup').removeClass('open');
                    // temp
                    $('.la-chapter-box.new-chap').removeClass('open');
                });
                $(".la-popup .button-save").on('click', function (event) {
                    event.preventDefault();
                    $(this).parents('.la-popup').removeClass('open');
                    // temp
                    $('.la-chapter-box.new-chap').addClass('open');
                });
            });
            $('la-popup.modal-lesson').each(function (index, el) {
                $(".la-popup .button-cancel").on('click', function (event) {
                    event.preventDefault();
                    $(this).parents('.la-popup').removeClass('open');
                    // temp
                    $('.la-lesson-box.new-chap').removeClass('open');
                });
                $(".la-popup .button-save").on('click', function (event) {
                    event.preventDefault();
                    $(this).parents('.la-popup').removeClass('open');
                    // temp
                    $('.la-chapter-box.new-chap').addClass('open');
                });
            });

            $(document).on('click', '.la-popup .button-cancel', function () {
                $('#videos-container').html('');
                $('.lema-button-add-video.lema-hide').removeClass('lema-hide');
                $('#audios-container').html('');
            });

            function countInput(inpt) {
                var max = inpt.attr("maxlength");
                var num = inpt.val().length;
                inpt.next().text(max - num);
            }

            $(".la-textbox-group").each(function (e) {
                var str = $(this).children("input").attr("maxlength");
                // alert(str);
                $(this).children(".la-input-length").html(str);
            });
            $('.la-textbox-group .la-input').each(function (index, el) {
                countInput($(this));
            });
            $(document).on('keyup', '.la-textbox-group .la-input', function () {
                countInput($(this));
            });

            // select2
            $('.la-categories-select').select2({
                placeholder: "Select categories"
            })
            $('.la-tags-select').select2({
                placeholder: "Select tags"
            })
            $('.la-select-lesson').select2({
                placeholder: 'Select Lesson'
            })
            $('.la-select-chapter').select2({
                placeholder: 'Select Chapter'
            });
            admin_ui.la_TinyMCE();
        },
        // drop file
        la_DropFile: function () {
            var drop = $(".drop-wrapper input");
            drop.on('dragenter', function (e) {
                $(".drop").css({
                    "border": "4px dashed #09f",
                    "background": "rgba(0, 153, 255, .05)"
                });
                $(".cont").css({
                    "color": "#09f"
                });
            }).on('dragleave dragend mouseout drop', function (e) {
                $(".drop").css({
                    "border": "3px dashed #DADFE3",
                    "background": "transparent"
                });
                $(".cont").css({
                    "color": "#8E99A5"
                });
                // alert($(this).parents('.la-tabs').html());
            });
            drop.on('drop dragend dragleave', function (e) {
                var setActiveID = $(this).parents('.la-tab-panel').attr('data-nextTab');
                $(this).parents('.la-tabs').find('.tab-link').removeClass('current');
                $(this).parents('.la-tabs').find('.la-tab-panel').removeClass('current');
                $(this).parents('.la-tabs').find('.tab-link[data-tab="' + setActiveID + '"] ').addClass('current');
                $(this).parents('.la-tabs').find('#' + setActiveID + '').addClass('current');
            });

            function handleFileSelect(evt) {


                var files = evt.target.files; // FileList object

                // Loop through the FileList and render image files as thumbnails.
                for (var i = 0, f; f = files[i]; i++) {

                    // Only process image files.
                    if (!f.type.match('image.*')) {
                        continue;
                    }
                    var reader = new FileReader();
                    // Closure to capture the file information.
                    reader.onload = (function (theFile) {

                        return function (e) {
                            // Render thumbnail.
                            var span = document.createElement('div');
                            span.className = "video-item";
                            span.innerHTML = ['<div class="thumbnail"><img class="pic" src="', e.target.result,
                                '" title="', escape(theFile.name), '"/></div><div class="la-check"></div>'
                            ].join('');
                            var list = document.getElementById('drop-video-result');
                            list.insertBefore(span, list.childNodes[0]);
                        };
                    })(f);

                    // Read in the image file as a data URL.
                    reader.readAsDataURL(f);
                }

            }

            $('#files').change(handleFileSelect);
        },
        active_tab: function (element) {
            $(element).addClass('current').siblings().removeClass('current');
            ;
            $('#' + $(element).attr('data-tab')).addClass('current').siblings().removeClass('current');
        },
        // La Tabs
        la_tabs: function () {
            $(document).on('click', 'ul.la-nav-tabs li', function () {
                admin_ui.active_tab(this);
                $('#la-modal').trigger($.Event('resize'));
            })
        },
        // La Input file
        la_inputFile: function () {
            var inputs = document.querySelectorAll('.la-file-input')
            for (var i = 0, len = inputs.length; i < len; i++) {
                customInput(inputs[i])
            }

            function customInput(el) {
                const fileInput = el.querySelector('[type="file"]')
                const label = el.querySelector('[data-js-label]')

                fileInput.onchange =
                    fileInput.onmouseout = function () {
                        if (!fileInput.value) return

                        var value = fileInput.value.replace(/^.*[\\\/]/, '')
                        el.className += ' -chosen'
                        label.innerText = value
                    }
            }
        },
        // sortable
        UiSortable: function () {
            $('.meta-box-sortables').sortable({disabled: true});
            $('.lema_ui-sortable').sortable({
                update: function (event, ui) {
                    var data = {
                        'action': 'lema_sort',
                        'data': [],
                    };
                    $(this).children().each(function (index, value) {
                        $(this).find('.la-chap-num:first').text('#' + (index + 1));
                        data.data[index] = $(this).data('id');
                    })
                    lema.request.post(lemaConfig.ajaxurl, data, function (res) {
                        if (res.code !== 200) {
                            alert(res.message);
                        }
                    })
                }
            }).disableSelection();
        },
        // popover
        la_Popover: function () {
            $('.la-popover-group .button').on('click', function (event) {
                event.preventDefault();
                /* Act on the event */
                $(this).parents('.la-popover-group').toggleClass('open');
            });
            $(document).click(function (event) {
                if (!$(event.target).closest('.la-popover-group').length) {
                    if ($('.la-popover-group').hasClass("open")) {
                        $('.la-popover-group').removeClass("open");
                    }
                }
            })
        },

        // Collapse course box
        la_collapse_box: function () {
            var listBox = ['lesson', 'chapter', 'quiz'];
            $.each(listBox, function (index, value) {
                var e = '.la-' + value + '-box .la-' + value + '-bar .la-btn-collapse, .la-' + value + '-box .la-' + value + '-bar .la-button-collapse, .la-chapter-box .hndle';
                $(document).on('click', e, function (e) {
                    if ($(e.target).is('[data-action]')) return;
                    e.preventDefault();

                    var this_click = $(this).parent('.la-' + value + '-box');
                    this_click.siblings().removeClass('open')
                    this_click.toggleClass('open', 100);

                });
            })
        },
        // Editable chapter title and lesson title
        la_chapter_editable: function () {
            // if ($('.la-course').length) {
            //     $('#wpfooter').css('position', 'relative');
            // }
            $('.la-chapter-bar .la-btn-edit').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.la-chapter-bar').toggleClass('has-editable');
            });

            // click cancel
            $('.la-chapter-bar .button-cancel').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.la-chapter-bar').toggleClass('has-editable');
                $(this).parents('.la-add-chapter-st').toggleClass('open');
                if ($(this).parents('.la-chapter-box').hasClass('new-chap')) {
                    $(this).parents('.la-chapter-box').removeClass('open');
                    $(this).parents('.la-chapter-box').removeClass('has-new');
                    $(this).parents('.la-chapter-bar').removeClass('has-editable');
                }
            });
            // click save
            $('.la-chapter-bar .button-save').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.la-chapter-bar').toggleClass('has-editable');
                $(this).parents('.la-add-chapter-st').toggleClass('open');

                if ($(this).parents('.la-add-chapter-st').length) {
                    $(this).parents('.la-add-chapter-st').addClass('open');
                    $(this).parents('.la-chapter-bar').removeClass('has-editable');
                }
            });

            // add new chapter
            $('.la-add-chapter-wrapp .button-new-chapter').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.la-lesson-box-wrapper').find('.la-chapter-box.new-chap').addClass('open has-new');
                $(this).parents('.la-lesson-box-wrapper').find('.la-chapter-box.new-chap .la-chapter-bar').toggleClass('has-editable');
            });

            // Lesson editable
            $('.la-lesson-bar .la-btn-edit').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.la-lesson-bar').toggleClass('has-editable');
                $(this).parents('.la-lesson-bar').next().toggleClass('has-edit');
            });
            // click cancel
            $('.la-lesson-bar .button-cancel').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.la-lesson-bar').toggleClass('has-editable');
                $(this).parents('.la-lesson-bar').next().toggleClass('has-edit');
            });
            // click save
            $('.la-lesson-bar .button-save').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.la-lesson-bar').toggleClass('has-editable');
                $(this).parents('.la-lesson-bar').next().toggleClass('has-edit');
                if ($(this).parents('.la-lesson-box').hasClass('has-new')) {
                    $(this).parents('.la-lesson-box').removeClass('has-new');
                    $(this).parents('.la-lesson-box').removeClass('add-new');
                    $(this).parents('.la-lesson-box').addClass('open');
                    $(this).parents('.la-lesson-box').find('.inside').removeClass('has-edit');
                }
            });
        },
        // Add Video content
        la_AddVideoContent: function () {
            // check item videos gallery
            $('.la-video-gallery').on('click', '.video-item', function (e) {
                e.preventDefault();
                /* Act on the event */
                $(this).toggleClass('active').siblings().removeClass('active');
            });
            // click add new video content st
            $('.btn-add-video').on('click', function (e) {
                e.preventDefault();
                /* Act on the event */
                $(this).parents('.inside').find('.la-lesson-video').addClass('active');
                $(this).parents('.inside').find('.la-st-add-content-lesson').addClass('hide');
            });
            $('.la-lesson-video .button-save').on('click', function (event) {
                event.preventDefault();
                /* Act on the event */
                $(this).parents('.la-lesson-video ').removeClass("active");
                $(this).parents('.inside').find('.la-st-add-content-lesson').removeClass('hide');
            });
            $('.la-lesson-video .button-cancel').on('click', function (event) {
                event.preventDefault();
                /* Act on the event */
                $(this).parents('.la-lesson-video ').removeClass("active");
                $(this).parents('.inside').find('.la-st-add-content-lesson').removeClass('hide');
            });
            $('.la-content-video-resource .remove-video').on('click', function (event) {
                event.preventDefault();
                $(this).parents('.la-content-video-resource').remove();
            });
        },
        // Add Video content
        la_AddArticleContent: function () {
            // click add new article content st
            $('.btn-add-article').on('click', function (e) {
                e.preventDefault();
                /* Act on the event */
                $(this).parents('.inside').find('.la-lesson-article').addClass('active');
                $(this).parents('.inside').find('.la-st-add-content-lesson').addClass('hide');
            });

            $('.la-lesson-article .button-save').on('click', function (event) {
                event.preventDefault();
                /* Act on the event */
                $(this).parents('.la-lesson-article ').removeClass("active");
                $(this).parents('.inside').find('.la-st-add-content-lesson').removeClass('hide');
            });
            $('.la-lesson-article .button-cancel').on('click', function (event) {
                event.preventDefault();
                /* Act on the event */
                $(this).parents('.la-lesson-article ').removeClass("active");
                $(this).parents('.inside').find('.la-st-add-content-lesson').removeClass('hide');
            });
        },
        // Add Slideshow content
        la_AddSlideshowContent: function () {
            // click add new article content st
            $('.btn-add-audio').on('click', function (e) {
                e.preventDefault();
                /* Act on the event */
                $(this).parents('.inside').find('.la-lesson-audio').addClass('active');
                $(this).parents('.inside').find('.la-st-add-content-lesson').addClass('hide');
            });
            $('.la-lesson-audio .button-save').on('click', function (event) {
                event.preventDefault();
                /* Act on the event */
                $(this).parents('.la-lesson-audio ').removeClass("active");
                $(this).parents('.inside').find('.la-st-add-content-lesson').removeClass('hide');
            });
            $('.la-lesson-audio .button-cancel').on('click', function (event) {
                event.preventDefault();
                /* Act on the event */
                $(this).parents('.la-lesson-audio ').removeClass("active");
                $(this).parents('.inside').find('.la-st-add-content-lesson').removeClass('hide');
            });
        },
        // TinyMCE
        la_TinyMCE: function () {
            tinyMCE.init({
                selector: '.tinymce-st-1',
                height: 150,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table contextmenu paste code autosave'
                ],
                setup: function (editor) {
                    editor.on('change', function () {
                        tinymce.triggerSave();
                    });
                },
                toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            });
            tinyMCE.init({
                selector: '.tinymce-st-2',
                height: 80,
                menubar: false,
                setup: function (editor) {
                    editor.on('change', function () {
                        tinymce.triggerSave();
                    });
                },
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table contextmenu paste code autosave'
                ],
                toolbar: ' styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image',

            });
        },
        la_CreateLesson: function () {
            $('.btn-add-new-lesson').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.inside').find('.la-lesson-list .la-lesson-box').removeClass('open')
                $(this).parents('.inside').find('.la-lesson-list .la-lesson-box.add-new').addClass('has-new');
                $(this).parents('.inside').find('.la-lesson-list .la-lesson-box.add-new .la-lesson-bar').addClass('has-editable');
            });

            $('.la-lesson-box.add-new.has-new .button-save').on('click', function (event) {
                event.preventDefault();
                /* Act on the event */
                alert("a");

                $(this).parents('.la-lesson-box').find('.la-lesson-bar.has-editable').removeClass('has-editable');

            });

            // Add new description for lesson
            $('.la-lesson-box .la-btn-add-des').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.la-lesson-box').addClass('has-description');

            });
            $('.la-description-st .add-new-desc .button-save').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.la-description-st').addClass('open');
                $(this).parents('.la-lesson-box').addClass('has-description');

            });
            $('.la-description-st .add-new-desc .button-cancel').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.la-description-st').removeClass('open');
                $(this).parents('.la-lesson-box').removeClass('has-description');

            });
            $('.la-description-st .description').on('click', function (event) {
                event.preventDefault();
                /* Act on the event */
                $(this).parents('.la-description-st').removeClass('open');
                // $(this).parents('.la-lesson-box').removeClass('has-description');
            });

            // Add new resource
            $('.la-lesson-box .la-btn-add-res').on('click', function (event) {
                event.preventDefault();
                $(this).parents('.la-lesson-box').addClass('has-resource');
                $(this).parents('.la-lesson-box').find('.la-resource-st').addClass('open');
            });
            $('.la-resource-st .la-action-bar .button-save').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.la-lesson-box').find('.la-resource-st').removeClass('open');
                $(this).parents('.la-lesson-box').find('.la-resource-st').addClass('has-source');
            });
            $('.la-resource-st .la-action-bar .button-cancel').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.la-lesson-box').find('.la-resource-st').removeClass('open');
                $(this).parents('.la-lesson-box').removeClass('has-resource');

            });
        },

        al_CreateQuiz: function () {
            // edit Quiz title
            $('.la-quiz-box .la-quiz-bar .la-btn-edit').on('click', function (e) {
                e.preventDefault();
                /* Act on the event */
                $(this).parents('.la-quiz-bar').toggleClass('has-editable');
                $(this).parents('.la-quiz-box').find('.inside').toggleClass('has-edit');
            });
            // new Quiz title
            $('.button-new-Quiz').on('click', function (e) {
                e.preventDefault();
                /* Act on the event */
                $(this).parents('.la-chapter-box').find('.new-quiz').toggleClass('open');
                $(this).parents('.la-chapter-box').find('.new-quiz .la-quiz-bar').toggleClass('has-editable');
                $(this).parents('.la-chapter-box').find('.new-quiz .la-quiz-bar .inside').toggleClass('has-edit');
            });
            // save edit title
            $('.la-quiz-box .la-quiz-bar .button-save').on('click', function (e) {
                e.preventDefault();
                /* Act on the event */
                $(this).parents('.la-quiz-bar').removeClass('has-editable');
                $(this).parents('.la-quiz-box').find('.inside').removeClass('has-edit');
            });
            $('.la-quiz-box .la-quiz-bar .button-cancel').on('click', function (e) {
                e.preventDefault();
                /* Act on the event */
                $(this).parents('.la-quiz-bar').removeClass('has-editable');
                $(this).parents('.la-quiz-box').find('.inside').removeClass('has-edit');
            });

            // add new quiz answer option
            $('.la-quiz-box .button-add-new').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.la-quiz-box').find('.inside').addClass('has-new');
            });
            // add edit quiz answer option
            $('.la-quiz-box .button-add-new').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.la-quiz-box').find('.inside').addClass('has-new');
            });
            // add save quiz
            $('.la-quiz-box .button-save').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.la-quiz-box').find('.inside').removeClass('has-new');

            });
            // add cancel quiz
            $('.la-quiz-box .button-cancel').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.la-quiz-box').find('.inside').removeClass('has-new');
                if ($(this).parents('.la-quiz-box').hasClass('new-quiz')) {
                    $(this).parents('.la-quiz-box').removeClass("open");
                }
            });
            // create new quiz
            $('.button-new-Quiz').on('click', function (event) {
                event.preventDefault();
                /* Act on the event */
                $(this).parents('.la-lesson-box').find('.la-quiz-box').removeClass("open");
                $(this).parents('.la-lesson-box').find('.la-quiz-box.new-quiz').addClass("open");
                $(this).parents('.la-lesson-box').find('.la-quiz-box.new-quiz .la-quiz-bar').addClass('has-editable');
            });
            // create new quiz
            $('.la-quiz-box .la-question-list .la-btn-edit').on('click', function (event) {
                event.preventDefault();
                /* Act on the event */
                $(this).parents('.la-lesson-box').find('.la-quiz-box').removeClass("open");
                $(this).parents('.la-lesson-box').find('.la-quiz-box.new-quiz').addClass("open");
                $(this).parents('.la-lesson-box').find('.la-quiz-box.new-quiz .la-quiz-bar').addClass('has-editable');
            });
        },
        // LA POPUP
        la_Popup: function () {
            var offsetDiv = Math.floor($('body').offset().left);
            var offsetDiv2 = Math.floor($(window).width());
            var offsetTop = Math.floor($('body').offset().top);
            $(".la-popup").draggable({
                // containment: "parent",
                handle: ".ui-draggable-handle",
                over: function (event, ui) {
                    ui.position.top = 0;
                },
                start: function (event, ui) {
                    ui.helper.bind("click.prevent", function (event) {
                        event.preventDefault();
                    });
                },
                drag: function (event, ui) {
                    // console.log(event.pageX);
                    if (ui.position.top <= 0) {
                        ui.position.top = 0;
                    }
                    if (event.pageX <= offsetDiv) {
                        // ui.position.left =  0;
                        $(this).addClass('stick-left');
                    } else {
                        $(this).removeClass('stick-left stick-right');
                        $(this).removeClass('cover-size');
                        $(this).removeClass('has-stickLeft has-stickRight');
                    }
                    if (event.pageX >= $(window).width()) {
                        // ui.position.left = Math.round(offsetDiv2 - $(this).width());
                        $(this).addClass('stick-right');
                    }
                },
                stop: function (event, ui) {
                    ui.position.top = 0;
                    ui.position.left = 0;
                    $(this).addClass('cover-size');
                    if (event.pageX <= offsetDiv) {
                        // alert();
                        $(this).addClass('has-stickLeft');
                    } else if (event.pageX >= $(window).width()) {
                        $(this).addClass('has-stickRight');
                    } else {
                        $(this).removeClass('has-stickLeft has-stickRight');
                    }

                }
            });
            $(".la-popup").resizable();
            $(".la-popup .button-collapse").on('click', function (e) {
                e.preventDefault();
                $(this).parents('.la-popup').toggleClass('la-collapsed');
            });

            $('#la-modal').bind('resize', function () {
                $(this).css({
                    'top': ($(window).height() - $(this).height()) / 3,
                    'left': ($(window).width() - $(this).width()) / 2
                });
            });
            $(document).on('click', '#submit-popup-form', function (e) {
                e.stopPropagation();
                e.preventDefault();
                $(this).html('<span class="fa fa-spin fa-spinner"></span> Loading...');
                if ($('.question-content.active').length) {
                    var type = $('.question-content.active').first().data('option');
                    if (type === 'newQuestion') {
                        $('#new-answer-form').submit();
                        return;
                    } else {
                        $('#choose-answer-form').submit();
                        return;
                    }
                }
                $('#la-modal').find('.pjaxform').submit();
                return false;
            });
            // open modal
            $(document).on('click', '[data-modal="la-modal"]', function (event) {
                var button = $(this);
                button.prop('disabled', true);

                //change to icon loading in button font-awesome
                var iconButton = button.find('.fa');
                var iconPrev = iconButton.attr('class');
                iconButton.attr('class', 'fa fa-spin fa-spinner');

                //excute action
                event.preventDefault();
                var idModal = $(this).data('modal');
                $('.la-popup').removeClass('open');
                var editTab = $(this).attr('data-tabcontent');
                var contentType = $(this).attr('data-contentType');

                var modal = $('#' + idModal);

                if (modal.length) {
                    var data = $(this).data();
                    data.course_id = $('#post_ID').val();
                    if (data.post_id) {
                        data.post_ID = data.post_id;
                    }
                    //data.action = 'ajax_get_modal';
                    //lema.ui.loading.show();
                    modal.find('h3.title').html('<span class="fa fa-spin fa-spinner"></span> Loading...');
                    $('#la-modal-content').html('<div class="la-loading"><span class="fa fa-spin fa-spinner"></span> Just a moment...</div>');
                    $('#submit-popup-form').html('<i class="fa fa-check"></i> Save');
                    modal.addClass('open');
                    if ($('.la-popup-backdrop').length == 0) {
                        $('body').append('<div class="la-popup-backdrop"></div>');
                    }

                    modal.trigger($.Event('resize'));
                    setTimeout(function () {
                        lema.request.post(ajaxurl, data, function (res) {
                            $('#la-modal-content').html(res.data);
                            modal.trigger($.Event('resize'));
                            if (editTab) {
                                modal.find('.la-popup-wrap >.la-tabs>.la-nav-tabs li').removeClass('current');
                                modal.find('.la-popup-wrap >.la-tabs>.la-nav-tabs li[data-tab=' + editTab + ']').addClass('current');
                                modal.find('.la-popup-wrap >.la-tabs>.la-tabs-content >.la-tab-panel').removeClass('current');
                                modal.find('.la-popup-wrap >.la-tabs>.la-tabs-content #' + editTab + '').addClass('current');
                            }
                            if (contentType) {
                                modal.find('.la-content-option .item').removeClass('active');
                                modal.find('.la-content-option .item[data-type="' + contentType + '"]').addClass('active');
                                modal.find('.la-lesson-main-content .la-lesson-content').removeClass('active');
                                modal.find('.la-lesson-main-content .la-lesson-content[data-type="' + contentType + '"]').addClass('active');
                            }
                            admin_ui.GeneralFunction();
                            button.prop('disabled', false);
                            iconButton.attr('class', iconPrev);
                            modal.find('h3.title').html(data.title);
                            lema.ui.loading.hide();

                        })
                    })
                } else {
                    alert('Modal not found !');
                    button.prop('disabled', false);
                }

            });

            //Lema open modal 2
            $(document).on('click', '[data-lema_modal]', function (e) {
                e.stopPropagation();
                e.preventDefault();
                var button = $(this);
                $('.la-popup-wrap .lema-save-button').prop('disabled', true);
                var targetModal = $('#' + button.data('lema_modal'));
                var backdrop = $('#lema-backdrop');
                var parent_id = button.data('parent_id') || button.data('post_parent');
                var title = button.data('title');
                var data = button.data();
                //active lai tab dau tien
                var tab_active = $(this).data('tab');
                if (tab_active == undefined || tab_active == '') {
                    admin_ui.active_tab(targetModal.find('.tab-link:first'));
                }

                if (backdrop) {
                    backdrop.show();
                } else {
                    $('<div/>', {
                        id: 'lema-backdrop'
                    });
                    $('body').append(backdrop);
                    setTimeout(function () {
                        backdrop.show();
                    }, 100);
                }
                targetModal.show();
                targetModal.find('h3.title').html(title);
                targetModal.css({
                    top: 50,
                    left: ($(window).width() - targetModal.width()) / 2
                });
                if (parent_id) {
                    targetModal.find('input[data-parent_id]').val(parent_id);
                }
                if (data.edit_action) {
                    var btn = targetModal.find('[type="button"]').first();
                    btn.prop('disabled', true);
                    var btnHtml = btn.html();
                    btn.html('<i class="fa fa-spin fa-spinner"></i> Loading...');
                    var form = targetModal.find('form').first();
                    form.find(':input').prop('disabled', true);
                    data['action'] = data.edit_action;

                    //delete data.edit_action;
                    lema.request.post(lema.config.ajaxurl, data, function (res) {
                        form.replaceWith($(res.data).find('form').first());
                        //btn.prop('disabled', true);
                        btn.html(btnHtml);
                        if (tab_active !== '') {
                            admin_ui.active_tab(targetModal.find('[data-tab=' + tab_active + ']'));
                        }
                    });
                }

            });
            $(document).on('keyup', 'form[data-container="#la-modal-content"] input', function (e) {
                $(this).parents('.la-popup-wrap').find('.lema-save-button').prop('disabled', false);
            })
            $(document).on('click', '.lema-popup .button-cancel', function (e) {
                e.preventDefault();

                var form = $(this).closest('.lema-popup').find('form').first();
                if (form) {
                    try {
                        form.find('.tinymce-st-2').each(function (i, e) {
                            tinymce.get($(e).attr('id')).setContent('')
                        })
                    } catch (e) {
                        lema.log(e);
                    }

                    form.find("input[type=text], textarea").val("");
                    form.find("input[name=post_ID]").val("");
                    form.find("input[name=post_parent]").val("");
                }
                $('#lema-backdrop').hide();
                $(this).closest('.lema-popup').hide();
            });
            $(document).on('click', '.lema-popup .lema-save-button', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var form = $(this).closest('.lema-popup').find('form.pjaxform');
                var btn = $(this);
                var btnHtml = btn.html();
                var self = this;
                if (form) {
                    var target = form.data('target');
                    tinyMCE.activeEditor.save();
                    var data = form.serialize();
                    var post_ID = form.find("input[name=post_ID]").val();
                    //set order item after create
                    if (post_ID == undefined || post_ID == '') {
                        var post_parent = form.find("input[name=post_parent]").val();
                        var total_item = $('#lema-' + target + '-' + post_parent + '>[data-id]').length;
                        data += '&menu_order=' + total_item;
                    }
                    // effect when submit
                    btn.html('<i class="fa fa-spin fa-spinner"></i> Loading...');
                    btn.prop('disabled', true);
                    form.find(':input').prop('disabled', true);

                    $.ajax({
                        type: 'POST',
                        url: lemaConfig.ajaxurl,
                        data: data
                    }).success(function (res) {
                        form.find(':input').prop('disabled', false);
                        btn.html(btnHtml);
                        //btn.prop('disabled', true);
                        // form[0].reset();
                        // form.find("input[type=text], textarea").val("");
                        var postID = form.find("input[name=post_ID]").val();
                        // form.find("input[name=post_ID]").val("");
                        // try {
                        //     $.each(tinymce.editors, function(i, e) {e.setContent('');});
                        // } catch (e) {
                        //     lema.log(e);
                        // }
                        var parent = form.find("input[name=post_parent]").val();
                        //form.find("input[name=post_parent]").val("");
                        if (res.message) {
                            lema.ui.toaster.success(res.message, 'Success');
                        }
                        if (target && lema.admin) {
                            if (target === 'chapter-list') {
                                lema.admin.reloadChapterList(postID);
                            }
                            // if (target === 'lesson-list') {
                            //     lema.admin.reloadLessonList(parent, postID);
                            // }
                            if (target === 'question-list') {
                                lema.admin.reloadQuestionList(parent, postID);
                            }
                            // if (target === 'quiz-list') {
                            //     lema.admin.reloadQuizList(parent, postID);
                            // }
                            if (target === 'data-list') {
                                lema.admin.reloadDataList(parent, postID);
                            }
                        }

                        // setTimeout(function(){
                        //     $(self).closest('.lema-popup').hide();
                        //     $('#lema-backdrop').hide();
                        // }, 100);
                    })
                }
            });

            // close modal
            $(document).on('click', '.la-popup .button-cancel', function (event) {
                event.preventDefault();
                $('.la-popup-backdrop').remove();
                $(this).parents('.la-popup').removeClass('open');
            });
            // choose content type
            $(document).on('click', '.la-content-option .inner', function (event) {
                event.preventDefault();
                /* Act on the event */
                var str = $(this).parents('.item').attr('data-type');
                $(this).parents('.la-content-option').find('.item').removeClass('active');
                $(this).parents('.item').addClass('active');
                $(this).parents('.la-lesson-main-content').find('.la-lesson-content').removeClass('active');
                $(this).parents('.la-lesson-main-content').find('.la-lesson-content[data-type="' + str + '"]').addClass('active');
            });
            // choose question option
            $(document).on('click', '.la-question-option .inner', function (event) {
                event.preventDefault();
                /* Act on the event */
                var str = $(this).parents('.item').attr('data-option');
                $(this).parents('.la-question-option').find('.item').removeClass('active');
                $(this).parents('.item').addClass('active');
                $(this).parents('.la-question-main-content').find('.question-content').removeClass('active');
                $(this).parents('.la-question-main-content').find('.question-content[data-option="' + str + '"]').addClass('active');
            });

        },
        // LA OPEN DIALOG
        la_dialog: function () {
            /*$(document).on('click','.la-button-remove', function(event) {
                event.preventDefault();
                /!* Act on the event *!/
                var data = $(this).data();
                $('.la-alert-dialog .button-yes').off('click').on('click', function(event) {
                    var btnYes = $(this);
                    btnYes.prop('disabled',true);
                    lema.request.post(ajaxurl,data,function(res){
                        $('#post_'+data.post_id).remove();
                        close_all_popup();
                        btnYes.prop('disabled',false);
                    })
                });
                $('.la-alert-dialog').addClass('open');
                $('body').append('<div class="la-popup-backdrop"></div>');
            });*/
            // close dialog
            $(document).on('click', '.la-alert-dialog .button-cancel', function (event) {
                event.preventDefault();
                $(this).parents('.la-alert-dialog').removeClass('open');
                $('.la-popup-backdrop').remove();
            });
        },

        // save modal temp
        la_saveModalContent: function () {
            $(' ').on('click', function (event) {
                // event.preventDefault();
                // var strC = $(this).parents('#modal-add-lesson').find('.la-content-option >.item.active').attr('data-type');
                // $('.la-lesson-detail .la-block-detail').removeClass('active');
                // $('.la-lesson-detail .la-block-detail[data-type="'+strC+'"]').addClass('active');
                // $('.la-popup-backdrop').remove();
                //       $(this).parents('.la-popup').removeClass('open');

            });

        },

        // Remove outer frame meta_box add new course
        remove_postbox: function () {
            var id = $('#create-new-course');
            id.removeClass('postbox');
            id.find('button.handlediv').remove();
            id.find('h2.hndle').remove();
        },
        // Add more input text
        Add_more_input: function () {
            $(document).on('click', '.block-add-more-option .button', function (event) {
                event.preventDefault();
                /* Act on the event */
                var ObjClone = $(this).parents('.block-add-more-option')
                    .find('.field:last-child').clone();
                ObjClone.find('input').val('');
                ObjClone.appendTo($(this).parent().find(".input-group"));
            });

            $(document).on('click', '.block-add-more-option .btn-del', function (event) {
                if ($(this).parents('.input-group').find('.field').length > 1) {
                    $(this).parent().remove();
                }
            });
        },
        close_alert: function () {
            $(document).on('click', '.dismiss', function (e) {
                $(this).parents('.notification').remove();
            })
        },
        save_course: function () {
            $(document).on('submit', '[data-lema]', function (event) {
                event.preventDefault();
                var post_type = $(this).find('[name=post_type]').val();
                var button = $(this).find('.button-save');
                var post_id = $(this).find('[name=post_parent]').val();
                var checkEdit = $(this).find('[name=post_ID]').val();
                var listInput = $(this).find('[name]').not('[type="hidden"]');
                button.prop('disabled', true);
                var data = $(this).serialize();
                lema.request.post(ajaxurl, data, function (res) {
                    //success
                    $.each(listInput, function (index, e) {
                        $(e).val('');
                    })
                    if (checkEdit == undefined) {
                        $('#list-' + post_type + '-' + post_id).append(res.data).fadeIn(300);
                        ;
                    } else {
                        $('#post_' + checkEdit).replaceWith(res.data);
                    }

                    button.prop('disabled', false);
                    close_all_popup();
                    var id = 'n_' + new Date().getTime().toString();
                    $('body').append('<div id="' + id + '" class="notification fade success" style="display: none;"><span class="dismiss"><a title="close"> x </a></span></div>')
                    $("#" + id).fadeIn("slow").append('<span><b>Success !</b> ' + res.message + '</span>');
                    setTimeout(function () {
                        $('#' + id).fadeOut(300, function () {
                            $(this).remove();
                        });
                    }, 4000);
                    admin_ui.GeneralFunction();
                });
            });
        },
        close_popup_simple: function () {
            $(document).on('click', '.la-popup-backdrop', function (event) {
                event.preventDefault();
                close_all_popup();
            });
            $(document).keydown(function (e) {
                switch (e.keyCode) {
                    case 27:
                        //esc key
                        close_all_popup();
                        break;
                }
            });
        },
        question_answer: function () {
            $(document).on('click', '.add-answer-item', function () {
                var answers = $('.answer-item');
                var ans = answers.last().clone();
                ans.find('input[type=text], input[type=radio], textarea').each(function (i, e) {
                    var newName = $(e).attr('name').replace(/\d+/g, answers.length);
                    $(e).attr('name', newName);
                    $(e).val('');
                });
                $('#answers-container').append(ans);

            });
            $(document).on('click', '.btn-remove-answer', function () {
                if ($('.answer-item').length == 1) return false;
                $(this).closest('.answer-item').remove();
            });
        },
        createAccodionMetaField: function () {
            if ($('.la_accodion').length > 0) {
                $(document).on('click', '.la_accodion label', function () {
                    $(this).parent().find('.la-block-content').slideToggle(0);
                });
            }
        },
        createTabVerticalMetaField: function () {
            if ($('.lema-tab-vertical').length > 0) {
                $('.lema-tab-vertical ul li:first-child').addClass('current');
                $('.lema-tab-vertical .lema-tab-content').first().addClass('current');
                $(document).on('click', '.lema-tab-vertical li', function () {
                    var data_tab = $(this).attr('data-tab');
                    $(this).parents('.lema-tab-vertical').find('.lema-tab-content').removeClass('current');
                    $(this).parents('.lema-tab-vertical').find('.' + data_tab).addClass('current');

                    $(this).parent().children().removeClass('current');
                    $(this).addClass('current');
                    return false;
                });
            }
        },
        customFields: function () {
            $('#customfield-types').on('change', function (e) {
                var val = $(this).find(":selected").val();
                switch (val) {
                    case 'list' :
                    case 'select' :
                    case 'radiolist' :
                    case 'checklist' :
                        $('#customfield-options').prop('disabled', false).show();
                        break;
                    default :
                        $('#customfield-options').prop('disabled', true).hide();
                }
            });

            $('#custom-field-label').on('blur', function (object) {
                var val = $(this).val();
                if (val.length > 0) {

                } else {
                    $('#custom-field-name').val('');
                }
            });
        }
    };

    function close_all_popup() {
        // $('.la-popup-backdrop').remove();
        // $('.la-popup').removeClass('open');
        // $('.la-alert-dialog').removeClass('open');
    }

    $(document).ready(function () {
        admin_ui.init();
    });

    $(document).ajaxSuccess(function () {
        admin_ui.UiSortable();
    });


})(jQuery);
