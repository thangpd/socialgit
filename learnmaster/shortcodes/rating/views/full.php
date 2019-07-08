<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
$object_id = $data['object_id'];
$user_id   = get_current_user_id();
?>
<div id="lema-rating-sc-<?php echo $object_id ?>"
     class="lema-rating lema-rating-full <?php $context->defineShortcodeBlock() ?>">
	<?php if ( ! $data['review_only'] ): ?>
    <div class="row">
        <div class="count-review-rating">
            <div class="leave-comment-title">Students review (<?php echo count( $list_comments ); ?>)</div>
            <button class="btn_view_more"><i class="fa fa-angle-up"></i></button>
			<?php echo '<div class="pagination" data-paged="1">'; ?>
            <div class="col-md-4 col-sm-6">
                <div class="lema-rating-statistic">
                    <label class="lema-shortcode lema-rating-label"><?php echo $data['label'] ?></label>
                    <label class="lema-shortcode lema-rating-value">
                        <strong><?php echo number_format( $status->avg, 1 ) ?></strong>
                    </label>
                    <div class="lema-star-rating view-only">
                        <span class="bg-rate"></span>
                        <span class="rating" style="width:<?php echo $status->avg * 20 ?>%"></span>
                    </div>
                    <label class="lema-shortcode lema-rating-total">
                        (<?php echo number_format( $status->total, 0 ) ?>
                        <small><?php echo __( 'ratings', 'lema' ) ?></small>
                        )
                    </label>

					<?php
					//check user commented
					if ( $user_id ):
						$check_comment = false;
						foreach ( $list_comments as $comment ) {
							if ( $comment->user_id == $user_id ) {
								$check_comment = true;
								break;
							}
						}
						if ( ! $check_comment ):
							?>
                            <div class="clearfix"></div>
							<?php
							if ( isset( $data['has_rating'] ) && $data['has_rating'] ) {
								echo $context->render( 'rating', [
									'context' => $context,
									'data'    => $data,
									'status'  => $status
								] );
							}
							?>
                            <div>
                            </div>
						<?php
						endif;
					endif;
					?>
                </div>
                <div class="lema-rating-details">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
						<?php
						$j            = 5;
						for ( $i = 1; $i <= 5; $i ++ ):
							$percent = 0;
							$star     = 'rate' . ( 5 - $i + 1 );
							$subTotal = (int) ( $status->$star );
							if ( $subTotal ) {
								$percent = intval( ( $subTotal / $status->total ) * 100 );
							}

							?>

                            <tr>
                                <td class="lema-rate-start widht-percent-50">
                                    <label class="full" title="<?php echo $i ?> stars"><?php echo $j;
										$j --; ?></label>
                                </td>
                                <td class="widht-percent-60">
                                    <div class="lema-rating-progress-container">
                                        <div class="lema-rating-progress" style="width: <?php echo $percent ?>%"></div>
                                    </div>

                                </td>
                                <td class="widht-percent-10">
									<?php
									echo $percent;
									?>%
                                </td>

                            </tr>
						<?php endfor; ?>
                    </table>
                </div>
            </div>
			<?php endif; ?>
			<?php
            $count = 2;
            $flag=1;
			foreach ( $list_comments as $index => $comment ) {
                $flag++;
				if ( ( $index + 1 ) % 6 == 0 ):
					echo '<div class="pagination" data-paged="' . $count ++ . '">';
				endif;
				$user_comment = get_user_by( 'id', $comment->user_id );
				?>
                <div class="col-md-4 col-sm-6">
                    <div class="leave-comment">
                        <div class="lema-comment">
                            <div class="comment-list">
                                <li class="comment even thread-even depth-1">
                                    <div class="comment-container">
                                        <!-- <?php echo get_avatar( $comment->user_id ) ?> -->
                                        <div class="comment-text">
                                            <p class="meta">
                                                <strong class="review-author">
													<?php
													if ( isset( $user_comment->display_name ) && ! empty( $user_comment->display_name ) ) {
														echo $user_comment->display_name;
													} else {
														echo $user_comment->user_login;
													}
													?>
                                                    <!-- <div class="lema-job-title">
                                            <?php the_author_meta( 'job_title', $user_comment->ID ) ?>
                                        </div> -->
                                                    <div class="lema-star-rating view-only">
                                                        <span class="bg-rate"></span>
                                                        <span class="rating"
                                                              style="width:<?php echo $comment->rate * 20 ?>%"></span>
                                                    </div>
                                                </strong>
                                            </p>
                                            <div class="description">
                                                <p><?php echo $comment->comment; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
				if ( $flag % 6 == 0  ):
					echo '</div>';
				endif;
			} ?>
        </div>
    </div>
</div>

