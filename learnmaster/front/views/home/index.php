<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 * @var WP_Post $page
 */

 ?>
<?php if ($isHome == false):?>
    <div class="lema-message error" id="lema-set-homepahe">
        <?php echo __('Would you like to set this page as your homepage? Click : ', 'lema')?>
        <a href="javascript:void(0)" class="ajax-button" data-action="set_lema_homepage" data-target="#lema-set-homepahe">here</a>
    </div>
<?php endif;?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <!--<header class="entry-header">
        <?php /*//the_title( '<h1 class="entry-title">', '</h1>' ); */?>
    </header><-->
    <div class="entry-content">
        <?php if(!empty($page)) :?>
        <?php
            echo apply_filters( 'the_content', $page->post_content );
        ?>
        <?php else :?>
            <div class="lema-message error">
                <?php echo __('No content for homepage found', 'lema')?>
            </div>
        <?php endif;?>
    </div><!-- .entry-content -->
</article><!-- #post-## -->
