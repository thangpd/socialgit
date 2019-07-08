<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 * @var \lema\shortcodes\bundle\BundleShortcode $context
 */


$post_id = $data['post_id'];
$layout  = $data['layout'];

if ( ! empty( $post_id ) ):?>
    <div class="item lema-bundle <?php $context->defineShortcodeBlock() ?>">
        <div class="bundle-item " data-attribute="item">
            <div class="bundle-wrapper">
                <div class="top-wrapper">
					<?php echo lema_do_shortcode( '[lema_bundlecard_image post_id="' . $post_id . '"]' ) ?>
                </div>
                <div class="middle-wrapper bundle-content-wrapper">
					<?php echo lema_do_shortcode( '[lema_bundlecard_category post_id="' . $post_id . '"]' ) ?>
					<?php echo lema_do_shortcode( '[lema_bundlecard_title post_id="' . $post_id . '"]' ) ?>
<!--					--><?php //echo lema_do_shortcode( '[lema_bundlecard_instructor post_id="' . $post_id . '"]' ) ?>
					<?php echo lema_do_shortcode( '[lema_bundlecard_description post_id="' . $post_id . '"]' ) ?>
                </div>
                <div class="bottom-wrapper">
					<?php echo lema_do_shortcode( '[lema_coursecard_bookmark post_id="' . $post_id . '"]' ) ?>
                    <?php echo lema_do_shortcode( '[lema_bundlecard_numview post_id="' . $post_id . '"]' ) ?>
                    <?php echo lema_do_shortcode( '[lema_bundlecard_price post_id="' . $post_id . '"]' ) ?>
                </div>
            </div>
        </div>
    </div>


<?php endif; ?>