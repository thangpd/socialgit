<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */

?>
<html>
<head>
    <?php wp_head(); ?>
</head>
<body <?php body_class();?>>
<div class="lema-content-wrapper">
    <?php echo lema()->page->execute();?>
</div>
</body>
<?php wp_footer()?>
</html>

