<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */

if ( isset( $data['template'] ) && ! empty( $data['template'] ) ) {
	$template = $data['template'];
	$template = str_replace( '{label}',
		'<label class="lema-shortcode lema-rating-label">' . $data['label'] . '</label>', $template );

	$template = str_replace( '{rating}',
		'<div class="lema-star-rating view-only">
      <span class="bg-rate"></span>
      <span class="rating" style="width:' . ( $status->avg * 20 ) . '%"></span>
    </div>', $template );

	$template = str_replace( '{number}',
		'<label class="lema-shortcode lema-rating-value">
        <strong>' . number_format( $status->avg, 1 ) . '</strong>
    </label>', $template );

	$template = str_replace( '{total}',
		'<div class="lema-number-rating">
        <label class="lema-shortcode lema-rating-total">
            (' . number_format( $status->total, 0 ) . ')
        </label>
    </div>', $template );

}
?>

<section id="Review" class="leave-comment">
    <div class="leave-comment-title"><?php echo __( 'Reviews', 'lema' ); ?></div>
	<?php if ( isset( $data['has_rating'] ) && $data['has_rating'] ): ?>
		<?php echo $context->render( 'rating', [ 'context' => $context, 'data' => $data, 'status' => $status ] ) ?>
	<?php endif; ?>
	<?php echo $context->render( 'rating_comment', array( 'data' => $data, 'list_comments' => $list_comments ) ) ?>
</section>