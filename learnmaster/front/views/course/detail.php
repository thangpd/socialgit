<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */

$data = $model->__data;
?>
<div class="lema-page-content course-detail">
    <div class="container">
        <div class="lema-course-detail">
            <section class="preface">
                <div class="preface-title title-course-detail"><?php the_title() ?></div>
                <div class="preface-sub-title sub-title-course-detail"><?php echo $model->course_subtitle ?></div>
                <div class="enroll-course">
					<?php
					$enroll = esc_html__( 'ENROLL NOW', 'lema' );
					echo lema_do_shortcode( '[lema_add_cart post_id="' . $post->ID . '" show_image="0" title="' . $enroll . '" class="lema-btn-cart"]' ) ?>

                </div>

            </section>

			<?php if ( ! empty( $urlVideo ) ): ?>
                <div class="lema-course-video">
                    <div class="block-video">
                        <video id="course-video" width="320" height="240" controls="false">
                            <source src="<?php echo esc_url( $urlVideo ) ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <a id="video-action" href="javascript:void(0)" class='btn btn-play-video'>
                            <i class="fa fa-play" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
			<?php endif; ?>
            <div class="meta-list-sidebar-content">
                <div class="row">
                    <div class="col-md-7">
						<?php
						if ( isset( $data['lema_course_sentence_repeator'] ) && ! empty( $data['lema_course_sentence_repeator'] ) ) {
							?>
                            <div class="meta_list">
								<?php
								foreach ( $data['lema_course_sentence_repeator'] as $val ) {
									?>
                                    <li>
                                        <div class="title"><?php echo esc_html( $val['lema_course_list_sen_title_text'] ); ?></div>
                                        <div class="description"><?php echo esc_html( $val['lema_course_list_sen_area'] ); ?></div>
                                    </li>
									<?php
								}
								?>
                            </div>
							<?php
						} ?>
                    </div>
                    <div class="col-md-5">
                        <div class="sidebar">
                            <div class="row">
                                <div class="title"><?php echo __( 'TIME', 'lema' ); ?></div>
                                <div class="subtitle">
									<?php echo esc_html( $data['course_length'] ); ?>
                                </div>
                                <div class="des">
									<?php echo esc_html( $data['course_effort'] ); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="title"><?php echo __( 'CLASSROOM OPENS', 'lema' ); ?></div>
                                <div class="subtitle">
									<?php echo esc_html( $data['lema_course_class_date_area'] ); ?>
                                </div>
                                <div class="des">
									<?php echo esc_html( $data['lema_course_class_open_area'] ); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="title"><?php echo __( 'ESTIMATED SALARY', 'lema' ); ?></div>
                                <div class="subtitle">
									<?php echo esc_html( $data['lema_course_salary_area'] ); ?>
                                </div>
                                <div class="des">
									<?php echo esc_html( $data['lema_course_based_on_area'] ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonial">
				<?php echo apply_filters( 'the_content', $data['lema_course_testimonial_area'] ); ?>
            </div>
            <div id="Rating" class="course-rating">
				<?php echo lema_do_shortcode( ' [lema_rating object_id="' . $post->ID . '"]' ); ?>
            </div>

			<?php
			$count               = 0;
			$pricing_box1_enable = lema()->config->lema_price_box1_enable;
			$pricing_box2_enable = lema()->config->lema_price_box2_enable;
			$pricing_box3_enable = lema()->config->lema_price_box3_enable;
			if ( $pricing_box1_enable ) {
				$count ++;
			}
			if ( $pricing_box2_enable ) {
				$count ++;
			}
			if ( $pricing_box3_enable ) {
				$count ++;
			}
			//if Column start learning
			if ( $count ):
				?>
                <div class="startlearning <?php echo 'column' . $count ?>">
                    <div class="title">
						<?php echo __( 'Start Learning Now', 'lema' ); ?>
                    </div>
                    <div class="subtitle">
						<?php echo __( 'Self-Study enrollees can upgrade to the special program at any time for an additional cost', 'lema' ); ?>
                    </div>
                    <div class="startlearning_blocks">
						<?php if ( $pricing_box1_enable ): ?>
                            <div class="block">
                                <div class="certificate">
									<?php
									$title = '';
									$title = lema()->config->lema_price_box1_title;
									if ( ! empty( $title ) ):
										?>
                                        <div class="title">
											<?php echo esc_html( $title ); ?>
                                        </div>
									<?php endif; ?>
                                    <div class="price">
										<?php echo lema_do_shortcode( ' [lema_coursecard_price post_id="' . $post->ID . '"]' ); ?>
                                    </div>
									<?php
									$subtitle = '';
									$subtitle = lema()->config->lema_price_box1_subtitle;
									if ( ! empty( $subtitle ) ):
										?>
                                        <div class="subtitle">
											<?php echo esc_html( $subtitle ); ?>
                                        </div>
									<?php endif; ?>
									<?php $des = '';
									$des       = lema()->config->lema_price_box1_description;
									if ( ! empty( $des ) ):
										?>
                                        <div class="description">
											<?php echo $des; ?>
                                        </div>
									<?php endif; ?>
                                    <div class="enroll-course">
										<?php
										$enroll = esc_html__( 'ENROLL NOW', 'lema' );
										echo lema_do_shortcode( '[lema_add_cart post_id="' . $post->ID . '" show_image="0" title="' . $enroll . '" class="lema-btn-cart"]' ) ?>

                                    </div>
                                </div>
                            </div>
						<?php endif; ?>
						<?php if ( $pricing_box2_enable ): ?>
                            <div class="block active">
                                <div class="monthly ">
									<?php
									$title = '';
									$title = lema()->config->lema_price_box2_title;
									if ( ! empty( $title ) ):
										?>
                                        <div class="title">
											<?php echo esc_html( $title ); ?>
                                        </div>
									<?php endif; ?>
									<?php
									$price          = '';
									$price          = lema()->config->lema_price_box2_price;
									$price_formated = lema()->helpers->general->currencyFormat( $price );
									$expired        = '';
									$expired        = lema()->config->lema_price_box2_expired;
									if ( ! empty( $price ) ):
										?>
                                        <div class="price" data-price="<?php echo $price ?>">
											<?php echo esc_html( $price_formated );
											?>
                                            <div class="expired">
												<?php
												if ( $expired == 0 ) {
													echo __( 'Unlimited', 'lema' );
												} else {
													echo __( 'Per Month', 'lema' );
												} ?></div>
                                        </div>
									<?php endif; ?>
									<?php
									$subtitle = '';
									$subtitle = lema()->config->lema_price_box2_subtitle;
									if ( ! empty( $subtitle ) ):
										?>
                                        <div class="subtitle">
											<?php echo esc_html( $subtitle ); ?>
                                        </div>
									<?php endif; ?>
									<?php $des = '';
									$des       = lema()->config->lema_price_box2_description;
									if ( ! empty( $des ) ):
										?>
                                        <div class="description">
											<?php echo $des; ?>
                                        </div>
									<?php endif; ?>
                                    <div class="enroll-course">
										<?php
										$enroll = esc_html__( 'ENROLL NOW', 'lema' );

										echo lema_do_shortcode( '[lema_checkout_bundle btn_text="' . $enroll . '" post_type="' . \lema\models\OrderModel::ORDER_EXPIRABLE . '" price="' . $price . '" expire_date="' . $expired . '"class="lema-btn-cart"]' ) ?>

                                    </div>
                                </div>
                            </div>
						<?php endif; ?>
						<?php if ( $pricing_box3_enable ): ?>
                            <div class="block">
                                <div class="vip">
									<?php
									$title = '';
									$title = lema()->config->lema_price_box3_title;
									if ( ! empty( $title ) ):
										?>
                                        <div class="title">
											<?php echo esc_html( $title ); ?>
                                        </div>
									<?php endif; ?>
									<?php
									$price          = '';
									$price          = lema()->config->lema_price_box3_price;
									$price_formated = lema()->helpers->general->currencyFormat( $price );
									$expired        = '';
									$expired        = lema()->config->lema_price_box3_expired;
									if ( ! empty( $price ) ):
										?>
                                        <div class="price" data-price="<?php echo $price ?>">
											<?php echo esc_html( $price_formated );
											?>
                                            <div class="expired">
												<?php
												if ( $expired == 0 ) {
													echo __( 'Unlimited', 'lema' );
												} else {
													echo __( 'Per Month', 'lema' );
												} ?></div>
                                        </div>
									<?php endif; ?>
									<?php
									$subtitle = '';
									$subtitle = lema()->config->lema_price_box3_subtitle;
									if ( ! empty( $subtitle ) ):
										?>
                                        <div class="subtitle">
											<?php echo esc_html( $subtitle ); ?>
                                        </div>
									<?php endif; ?>
									<?php $des = '';
									$des       = lema()->config->lema_price_box3_description;
									if ( ! empty( $des ) ):
										?>
                                        <div class="description">
											<?php echo $des; ?>
                                        </div>
									<?php endif; ?>
                                    <div class="enroll-course">
										<?php
										$enroll = esc_html__( 'ENROLL NOW', 'lema' );

										echo lema_do_shortcode( '[lema_checkout_bundle btn_text="' . $enroll . '" post_type="' . \lema\models\OrderModel::ORDER_EXPIRABLE . '" price="' . $price . '" expire_date="' . $expired . '"]' ) ?>

                                    </div>
                                </div>
                            </div>
						<?php endif; ?>
                    </div>
                </div>
			<?php endif;
			//end column start learing;
			?>
			<?php
			//choose the learning block
			$arr                    = array(
				'_content'            => __( 'Content And Commynity', 'lema' ),
				'_person_support'     => __( 'Personalized support', 'lema' ),
				'_additional_feature' => __( 'Additional features', 'lema' )
			);
			$choose_learning_format =
				'<div class="choose_model">
                      <div class="title">' . __( 'Choose The Learning Model That & Right For You', 'lema' ) . ' </div>
                            <table class="list">
                                <tr>
                                    <th></th>
                                    <th class="certificate">' . __( 'Certificate', 'lema' ) . '</th>
                                    <th class="self_study">' . __( 'Self - study', 'lema' ) . '</th>
                                </tr>
                                %1$s            
                            </table>          
                </div>';
			$choose_learn           = '';
			foreach ( $arr as $key => $val ) {
				$choose_learn .= '
                    <tr>
                        <td colspan="3" class="title space">' . esc_html( $val ) . '</td>	        
                    </tr>
                    %1$s';
				?>
				<?php
				$meta_value = '';
				if ( ! empty( $data[ 'lema_course' . $key . '_list_meta_repeator' ] ) ):
					foreach ( $data[ 'lema_course' . $key . '_list_meta_repeator' ] as $vl ):
						$meta_value .= '<tr>';
						$meta_value .= '<td class="title">' . $vl[ "lema_course" . $key . "_text" ] . '</td>';
						if ( isset( $vl[ 'lema_course' . $key . 'certificate_checkbox' ] ) && $vl[ 'lema_course' . $key . 'certificate_checkbox' ] === 'on' ) {
							$meta_value .= '<td class="certificate_checkbox on"><i class="fa fa-check"></i></td>';
						} else {
							$meta_value .= '<td class="certificate_checkbox"><i class="fa fa-times"></i></td>';
						}
						if ( isset( $vl[ 'lema_course' . $key . 'self_checkbox' ] ) && $vl[ 'lema_course' . $key . 'self_checkbox' ] === 'on' ) {
							$meta_value .= '<td class="self_study_checkbox on"><i class="fa fa-check"></i></td>';
						} else {
							$meta_value .= '<td class="certificate_checkbox"><i class="fa fa-times"></i></td>';
						}
						$meta_value .= '</tr>';
					endforeach;
				endif;

				$choose_learn = sprintf( $choose_learn, $meta_value );
			}

			if ( ! empty( $choose_learn ) ) {
				printf( $choose_learning_format, $choose_learn );
			}
			//end choose learning block;

			?>

            <div class="faq_block">
                <div class="title"><?php echo __( 'Frequently Asked Questions', 'lema' ); ?></div>
                <div class="faq_block_content">
					<?php if ( isset( $data['lema_course_faq_repeator'] ) && ! empty( $data['lema_course_faq_repeator'] ) ) {
						foreach ( $data['lema_course_faq_repeator'] as $val ) {
							?>
                            <div class="col-md-6">
                                <div class="title"><a
                                            href="#"><?php echo esc_html( $val['lema_course_faq_title_text'] ); ?></a>
                                </div>
                                <div class="des"><?php echo esc_html( $val['lema_course_faq_des_area'] ); ?></div>
                            </div>
							<?php
						}
					} ?>
                </div>
            </div>
        </div>
    </div>
    <div class="lema-sidebar-anchor"></div>
</div>

