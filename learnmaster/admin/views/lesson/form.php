<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 *
 * @var \lema\helpers\Helper $helper
 * @var \lema\admin\controllers\LessonController $context
 * @var \lema\models\LessonModel $model
 */
?>




<div id="la-modal-lesson" class="la-popup lema-popup">
    <div class="la-popup-wrap">
        <div class="la-popup-header ui-draggable-handle">
            <div class="button-collapse" title="Collapse popup">
                <span class="flaticon-arrows-7"></span>
            </div>
            <h3 class="title"><?php //echo $post->title; ?></h3>
            <span class="la-button button-save" title="Save changes (ctrl + s)"><span
                        class="flaticon-checked"></span></span>
            <span class="la-button button-cancel" title="Cancel & close popup"><span
                        class="fa fa-close"></span></span>
        </div>
        <div id="la-modal-lesson-content">

            <?php lema()->helpers->general->registerPjax('la-modal-content')?>
            <form class="pjaxform" data-chapter_id="<?php echo $postParent?>" data-container="#la-modal-content" data-target="data-list"  action="<?php echo admin_url('admin-ajax.php')?>"  method="post">
                <input type="hidden" name="action" value="ajax_save_lesson" />
                <input type="hidden" name="post_type" value="lesson" />
                <?php if(!empty($post_id)):?>
                    <input type="hidden" name="post_ID" value="<?php echo $post_id?>" />
                <?php endif;?>
                <input data-parent_id type="hidden" name="post_parent" value="<?php echo !empty($postParent) ? $postParent : ''?>" />

                <div class="la-tabs">
                    <ul class="la-nav-tabs">
                        <li class="tab-link current" data-tab="tab-12">General</li>
                        <li class="tab-link" data-tab="tab-13">Content</li>
                        <li class="tab-link" data-tab="tab-14">Add resources</li>
                    </ul>
                    <div class="la-tabs-content la-popup-body">
                        <?php if (isset($message)) :?>
                            <div class="la-tabs-content la-popup-body">
                                <div id="tab-10" class="la-tab-panel current">
                                    <div class="notice notice-success is-dismissible">
                                        <p><?php _e( $message, 'lema' ); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php else :?>

                            <div id="tab-12" class="la-tab-panel current">
                                <div class="la-form-group">
                                    <label>Lesson title</label>
                                    <input  type="text" name="post_title" class="la-form-control" value="<?php echo ($model->post) ? $model->post_title : ''?>" />
                                </div>
                                <div class="la-form-group">
                                    <label>Lesson description</label>
                                    <textarea  type="text" name="post_content" rows="5" class="la-form-control tinymce-st-2"><?php echo ($model->post) ? $model->post_content : ''?></textarea>
                                </div>
                            </div>
                            <div id="tab-13" class="la-tab-panel">
                                <div class="la-lesson-main-content">
                                    <label class="lb-text">Select the main type of content. Files and links can be added as resources.</label>
                                    <?php
                                    lema()->helpers->form->generateFormElement($model, $form);
                                    echo lema()->helpers->form->getField('content_type');
                                    ?>

                                </div>
                            </div>
                            <div id="tab-14" class="la-tab-panel">
                                <div class="la-form-group">
                                    <strong>Tip: </strong>A resource is for any type of document that can be used to help students in the lecture. This file is going to be seen as a lecture extra. Make sure everything is legible and the file size is less than 1 GiB.
                                </div>
                                <div class="la-tabs">
                                    <ul class="la-nav-tabs">
                                        <li class="tab-link current" data-tab="tab-20">Downloadable Files</li>
                                        <li class="tab-link" data-tab="tab-21">External Resources</li>
                                        <li class="tab-link" data-tab="tab-22">Source Code</li>
                                    </ul>
                                    <div class="la-tabs-content la-popup-body">
                                        <div id="tab-20" class="la-tab-panel current">
                                            <?php
                                            echo $helpers->form->getField('resource_downloadable');
                                            ?>


                                        </div>
                                        <div id="tab-21" class="la-tab-panel">
                                            <?php
                                            echo $helpers->form->getField('resource_external');
                                            ?>

                                        </div>
                                        <div id="tab-22" class="la-tab-panel">
                                            <?php
                                            echo $helpers->form->getField('resource_code');
                                            ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif;?>
                    </div>


                </div>
                <script language="javascript">
                    ;(function($){
                        $(document).ready(function(){
                            tinymce.init({
                                selector: '.tinymce-st-2, .tinymce-st-1',
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
                                    editor.on('keyup', function(){
                                        var save_btn = $('.la-popup-wrap').find('.lema-save-button');
                                        if(save_btn !== undefined){
                                            save_btn.prop('disabled', false);
                                        }
                                    })
                                },
                                toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                            });
                        });
                    })(jQuery)
                </script>
            </form>

            <?php lema()->helpers->general->endPjax()?>

        </div>
        <div class="la-popup-footer">
            <div class="la-controls">
                <button type="button" class="button button-tertiary flat lema-save-button" disabled>
                    <i class="fa fa-save"></i> <?php echo __('Save changes', 'lema')?>
                </button>
                <button type="button" class="button button-secondary flat button-cancel"><span
                            class="fa fa-close"></span> <?php echo __('Close' ,'lema')?>
                </button>
            </div>
        </div>
    </div>

</div>


