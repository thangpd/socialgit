<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
 ?>
<?php lema()->helpers->general->registerPjax('save-setting-general', 'div')?>
<div class="lema-setting">
    <?php if(isset($message)):?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e( $message, 'lema' ); ?></p>
        </div>
    <?php endif;?>

    <h2><?php echo __('General settings')?></h2>
    <div class="lema-col-50">
        <form data-container="#save-setting-general" data-pjax action="<?php echo admin_url('admin-ajax.php')?>" method="post">
            <input type="hidden" name="action" value="setting-general-save" />
            
            <?php foreach ($fields as $field) :?>
                <div class="la-form-group">
                    <?php echo $field?>
                </div>
            <?php endforeach;?>
            
            <div class="la-form-group">
                <button class="button button-primary" type="submit"><?php echo __('Save changes', 'lema')?></button>
            </div>
        </form>
    </div>
    <div class="lema-col-50"></div>
    <script language="javascript">
        lema.ui.select2();
    </script>
</div>
<?php lema()->helpers->general->endPjax('div')?>

