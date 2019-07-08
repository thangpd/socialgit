<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
if (!defined('LEMA_PATH')) exit;
 ?>
<h2><?php echo __('Setting Paypal Account', 'lema')?></h2>
<?php if(isset($message)):?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e( $message, 'lema' ); ?></p>
    </div>
<?php endif;?>
<form method="post" autocomplete="off">
    <div class="la-form-group">
        <label><?php echo __('Account email', 'lema')?></label>
        <input type="text" name="Paypal[email]" class="la-form-control" value="<?php echo isset($email) ? $email : ''?>"/>
    </div>
    <div class="la-form-group">
        <label><?php echo __('Account password', 'lema')?></label>
        <input type="password" class="la-form-control" autocomplete="off" name="password" id="password_fake" value="" style="display:none;" />
        <input type="password" class="la-form-control" autocomplete="off" name="Paypal[password]" value="<?php echo isset($password) ? $password : ''?>"/>
    </div>
    <div class="la-form-group">
        <label><?php echo __('Account signature', 'lema')?></label>
        <input type="password" class="la-form-control" autocomplete="off" name="Paypal[signature]" value="<?php echo isset($signature) ? $signature : ''?>"/>
    </div>
    <div class="la-form-group">
        <label>
            <input type="checkbox" class="la-form-control" autocomplete="off" <?php echo isset($sandbox) ? 'checked' : ''?> name="Paypal[sandbox]" value="1"/>
            <?php echo __('Sandbox enabled?', 'lema')?>
        </label>
    </div>
    <div class="la-form-group">
        <button type="submit" class="button button-primary">
            <?php echo __('Save changes', 'lema')?>
        </button>
    </div>
</form>