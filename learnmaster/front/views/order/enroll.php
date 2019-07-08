<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
 ?>

<div class="lema-cart-checkout">
	<div class="lema-message success">
	    <?php echo esc_html__("Thank you. You have just enrolled to course : {$course->post->post_title}. The browser redirecting to course dashboard...", 'lema')?>
	</div>
	<script language="javascript">
	    setTimeout(function(){
	        window.location.href = "<?php echo $dashboardUrl?>";
	    }, 5000);
	</script>
</div>