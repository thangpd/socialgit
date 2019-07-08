<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 * @var \lema\models\OrderModel $order
 */
 ?>
<?php if (!empty($approvalUrl)):?>
    <div class="lema-loading">
        <?php echo __('Please wait...Payment request is being processing.')?>
        <script language="javascript">
            window.location.href = "<?php echo $approvalUrl?>";
        </script>
    </div>
<?php endif;?>
