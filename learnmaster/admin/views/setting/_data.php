<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
 ?>

<?php lema()->helpers->general->registerPjax('data-setting-result', 'div')?>
   <div class="lema-setting">
       <h2><?php echo __('Page Urls', 'lema')?></h2>
       <hr/>
       <?php if(isset($message)):?>
           <div class="notice notice-success is-dismissible">
               <p><?php _e( $message, 'lema' ); ?></p>
           </div>
       <?php endif;?>
       <form method="post" data-container="#data-setting-result" data-pjax action="<?php echo admin_url('admin-ajax.php')?>">
           <input type="hidden" name="action" value="setting-data-save" />
           <?php foreach ($pages as $name => $field):?>
               <div class="la-form-group">
                   <?php echo $field?>
               </div>
           <?php endforeach;?>
           <div class="la-form-group">
               <button class="button button-primary" type="submit"><?php echo __('Save changes', 'lema')?></button>
           </div>
       </form>
   </div>
<?php lema()->helpers->general->endPjax('div')?>
<style>
    .config-url .slug_url:before{
        content: "<?php echo site_url()?>/";
    }
</style>