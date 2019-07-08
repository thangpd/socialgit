<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 * @var WP_Term $term
 */
 ?>
<div class="lema-course-list-wrapper">
    <h2 class="lema-course-list-title"><?php echo $term->name?></h2>
    <?php echo lema_do_shortcode("[lema_course_list_category cols_on_row='4' cat='{$term->slug}']")?>

</div>