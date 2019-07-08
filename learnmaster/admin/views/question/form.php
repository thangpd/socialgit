<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 *
 * @var \lema\admin\controllers\QuestionController $context
 */
?>



<div id="la-modal-question" class="la-popup lema-popup">
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
        <div id="la-modal-question-content">
            <div class="la-tabs">
                <ul class="la-nav-tabs">
                    <!-- <li class="tab-link current" data-tab="tab-10">General</li> -->
                </ul>
                <div class="la-tabs-content la-popup-body">
                    <div id="tab-19" class="la-tab-panel current">
                        <?php if (isset($message)) : ?>
                            <div class="la-tabs-content la-popup-body">
                                <div id="tab-10" class="la-tab-panel current">
                                    <div class="notice notice-success is-dismissible">
                                        <p><?php _e($message, 'lema'); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php else : ?>
                        <?php //echo $context->set_input_edit_post($post); ?>
                        <p>You can choose already question from questions list or you can create a new question</p>
                        <div class="la-question-main-content">
                            <div class="question-content active" data-option="newQuestion">
                                <form class="pjaxform" id="new-answer-form" data-quiz_id="<?php echo $postParent ?>"
                                      data-container="#la-modal-content" data-target="question-list"
                                      action="<?php echo admin_url('admin-ajax.php') ?>" method="post">
                                    <input type="hidden" name="action" value="ajax_save_question"/>
                                    <?php if (!empty($post_id)): ?>
                                        <input type="hidden" name="post_ID" value="<?php echo $post_id ?>"/>
                                    <?php endif; ?>
                                    <input data-parent_id type="hidden" name="post_parent" value="<?php echo !empty($postParent) ? $postParent : '' ?>"/>

                                    <input type="hidden" name="post_type" value="question"/>
                                    <?php $context->render('_form', [
                                        'model' => $model,
                                        'form' => $form,
                                        'lessons' => $lessons
                                    ]) ?>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>


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