<?php
?>


<div class="lema-comment" data-data="<?php echo urlencode( json_encode( $data ) ); ?>">
    <ol class="comment-list">
		<?php
		foreach ( $list_comments as $comment ) {
			$user_comment = get_user_by( 'id', $comment->user_id );
			$jobtitle     = get_user_meta( $user_comment->data->ID, 'job_title', true );
			?>
            <li class="comment even thread-even depth-1">
                <div class="comment-container">

					<?php echo get_avatar( $comment->user_id );
					if ( ! empty( $jobtitle ) ) {
						?>
                        <div class="jobtitle"> <?php echo esc_html( $jobtitle ); ?></div>
						<?php
					}
					?>
                    <div class="comment-text">
						<?php if ( current_user_can( 'administrator' ) ):
							?>
                            <button type="button" class="close delete-rating" aria-label="Close"
                                    data-action="lema_delete_rating"
                                    data-id="<?php echo $comment->id ?>">
                                <span aria-hidden="true">&times;</span>
                            </button>
						<?php endif; ?>
                        <div class="comment_direction">
                            <div class="description">
                                <p><?php
									echo $comment->comment; ?>
                                </p>
                            </div>

                            <div class="info_review">
                                <p class="meta">
                                    <strong class="review-author">
										<?php
										if ( isset( $user_comment->display_name ) && ! empty( $user_comment->display_name ) ) {
											echo $user_comment->display_name;
										} else {
											echo $user_comment->user_login;
										}
										?>
                                        <div class="lema-job-title">
											<?php the_author_meta( 'job_title', $user_comment->ID ) ?>
                                        </div>
                                    </strong>
                                </p>
                            </div>
                        </div>
                        <div class="lema-star-rating view-only">
                            <span class="bg-rate"></span>
                            <span class="rating" style="width:<?php echo $comment->rate * 20 ?>%"></span>
                        </div>
                    </div>
                </div>
            </li>
		<?php } ?>
    </ol>
</div>
