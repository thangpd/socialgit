<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 * @var \lema\models\Student $student
 */
 ?>
<?php if($status) :?>
    <div class="lema-cart-checkout">
        <div class="lema-message success">
            <?php echo __('Your order has been processed successfully. Redirecting you to your dashboard...', 'lema');?>

        </div>
        <hr />
        <small><?php echo __(sprintf('Click %s if browser did not redirect you', '<a href="' . $student->getProfileUrl() . '"> here </a>"'), 'lema')?></small>
        <script language="javascript">
            setTimeout(function(){
                window.location.href = '<?php echo $student->getProfileUrl()?>';
            }, 5000);
        </script>
    </div>
<?php else :?>
    <div class="lema-cart-checkout">
        <div class="lema-message error">
            <?php echo __('Can not process your payment. Please try again or contact to us.', 'lema')?>
        </div>
    </div>
<?php endif;?>
