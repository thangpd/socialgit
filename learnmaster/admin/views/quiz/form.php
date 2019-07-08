<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
?>
<div id="la-modal-quiz" class="la-popup lema-popup">
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
        <div id="la-modal-quiz-content">

            <?php lema()->helpers->general->registerPjax('la-modal-content')?>
            <form class="pjaxform" data-chapter_id="<?php echo $postParent?>" data-container="#la-modal-content" data-target="data-list"  action="<?php echo admin_url('admin-ajax.php')?>" method="post">
                <input type="hidden" name="action" value="ajax_save_quiz" />
                <?php if(!empty($post_id)):?>
                    <input type="hidden" name="post_ID" value="<?php echo $post_id?>" />
                <?php endif;?>
                <input data-parent_id type="hidden" name="post_parent" value="<?php echo !empty($postParent) ? $postParent : ''?>" />
                <input type="hidden" name="post_type" value="quiz" />

                <div class="la-tabs">
                    <?php if (isset($message)) :?>
                        <div class="la-tabs-content la-popup-body">
                            <div id="tab-10" class="la-tab-panel current">
                                <div class="notice notice-success is-dismissible">
                                    <p><?php _e( $message, 'lema' ); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php else :?>
                        <ul class="la-nav-tabs">
                            <li class="tab-link current" data-tab="tab-18">General</li>
                        </ul>
                        <div class="la-tabs-content la-popup-body">
                            <div id="tab-18" class="la-tab-panel current">
                                <?php
                                $fields = lema()->helpers->form->generateFormElement($model, $form);
                                foreach ($fields as $field):
                                    ?>
                                    <?php echo $field?>
                                <?php endforeach;?>
                            </div>
                        </div>

                    <?php endif;?>
                </div>
                <script language="javascript">
                    ;(function($){
                        $(document).ready(function(){
                            tinymce.init({
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
