<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
 ?>
<h2>Support</h2>
<?php if(isset($message)):?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e( $message, 'lema' ); ?></p>
    </div>
<?php endif;?>
<form method="post" autocomplete="off" id="lema-ticket-form">
    <input type="hidden" name="action" value="send_ticket" />
    <div class="la-form-group">
        <label><?php echo __('Your email', 'lema')?></label>
        <input required type="text" name="Support[email]" class="la-form-control" value="<?php echo get_option('admin_email')?>"/>
    </div>
    <div class="la-form-group">
        <label><?php echo __('Message', 'lema')?></label>
        <textarea rows="10" class="la-form-control" autocomplete="off" name="Support[message]" ></textarea>
    </div>
    <div class="la-form-group">
        <label>
            <input type="checkbox" class="la-form-control" autocomplete="off" checked  name="Support[log]" value="1"/>
            <?php echo __('Attach error logs', 'lema')?>
        </label>
    </div>
    <div class="la-form-group">
        <button type="submit" class="button button-primary">
            <?php echo __('Send ticket', 'lema')?>
        </button>
    </div>
</form>
