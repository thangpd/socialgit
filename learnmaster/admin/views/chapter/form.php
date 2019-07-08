<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
?>

<div id="la-modal-chapter" class="la-popup lema-popup">
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
        <div id="la-modal-chapter-content">
            <?php lema()->helpers->general->registerPjax('la-modal-content')?>
            <form class="pjaxform" data-container="#la-modal-content" data-target="chapter-list" action="<?php echo admin_url('admin-ajax.php')?>" method="post">
                <input type="hidden" name="action" value="ajax_save_chapter" />
                <?php if(!empty($post_id)):?>
                    <input type="hidden" name="post_ID" value="<?php echo $post_id?>" />
                <?php endif;?>
                <input data-parent_id type="hidden" name="post_parent" value="<?php echo (isset($postParent) && !empty($postParent))? $postParent : ''?>" />
                <input type="hidden" name="post_type" value="<?php echo $postType?>" />
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
                            <li class="tab-link current" data-tab="tab-10">General</li>
                        </ul>
                        <div class="la-tabs-content la-popup-body">
                            <div id="tab-10" class="la-tab-panel current">

                                <?php
                                $fields = lema()->helpers->form->generateFormElement($model, $form);
                                foreach ($fields as $field) :
                                    ?>
                                    <?php echo $field?>
                                <?php endforeach;?>

                            </div>
                        </div>
                    <?php endif;?>
                </div>
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

