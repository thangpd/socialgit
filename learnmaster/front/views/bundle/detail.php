<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


$html_format = array(
	'course_bundle'     => '<div class="course_title">
                            <a href="%1$s">%2$s</a>
                        </div>
                        <div class="commitment">
                            <div class="text">%3$s</div>
                            <div class="value">%4$s</div>
                        </div>
                        <div class="subtitles">
                            <div class="text">%5$s</div>
                            <div class="value">%6$s</div>
                        </div>
                        <hr>
                        <div class="about">
                            <div class="title">%7$s</div>
                            <div class="des_about">%8$s</div>

                        </div>
                        <div class="course_detail_hide" id="course_detail">
                            %9$s
                        </div>',
	'course_sub_bundle' => '<div class="course_sub">
                    <div class="title"><a href="%1$s">%2$s</a></div>
                    %3$s
                </div>',

);

$course_list = \lema\models\BundleModel::get_list_course_bundle( $model->post->ID );


?>
<div class="lema-page-content bundle-detail">
    <div class="container">
        <div class="row">

            <div class="col-md-3">

                <div class="lema-sidebar-anchor" id="lema-sidebar-anchor">
                    <div class="about">
                        <a href="#lema-row-about"><?php echo __( 'About', 'lema' ); ?></a>
                    </div>
                    <div class="course">
                        <a href="#lema-row-course"><?php echo __( 'Course', 'lema' ); ?></a>
                    </div>
                    <div class="creators">
                        <a href="#lema-row-creator"><?php echo __( 'Creators', 'lema' ); ?></a>
                    </div>
                    <div class="faq">
                        <a href="#lema-row-faq"><?php echo __( 'FAQ', 'lema' ); ?></a>
                    </div>
                    <div class="title">
						<?php echo esc_html( $model->post->post_title ) ?>
                    </div>
                    <div class="enroll-btn">

						<?php

						//		'post_type'   => '',
						//			'expire_date' => '1',//expired in number month.
						//			'post_id'     => 0,
						//			'price'       => 10,
						//      'btn_text'        => __( 'Enroll', 'lema' ),
						//		'btn_text_bought' => __( 'Enrolled', 'lema' ),
						//		do_shortcode('[lema_checkout_bundle]')
						echo do_shortcode( '[lema_checkout_bundle  price=' . $model->__data["lema_sale_price"] . ' post_type="' . $model->post->post_type . '" post_id="' . $model->post->ID . '" btn_text="' . __( "Enroll Now", "lema" ) . '"]' ) ?>


                    </div>
                </div>

            </div>

            <div class="col-md-9">

                <div class="course-bundle-content">
                    <div class="lema-row lema-row-title">
                        <div class="title_bundle">
							<?php echo esc_html( $model->post->post_title ) ?>
                            <a
                                    href="<?php echo esc_url( get_permalink( $model->post->ID ) ); ?>"></a>
                        </div>
                        <div class="subtitle_bundle"><?php echo esc_html( $model->__data['lema_subtitle_text'] ); ?></div>
                    </div>
                    <div class="lema-row lema-row-about" id="lema-row-about">
                        <div class="about_row">
                            <div class="title"><?php echo __( 'About this Specialization', 'lema' ); ?></div>
							<?php echo apply_filters( 'the_content', $model->__data['lema_about_area'] ); ?>
                        </div>
                    </div>
					<?php if ( $model->__data['created_by_conditinal']['enabled'] === 'on' ): ?>
                        <div class="lema-row lema-row-created">
                            <div class="title"><?php echo __( 'Created By', 'lema' ); ?></div>
                            <div class="title_img">
                                <img src="<?php echo $model->__data['created_by_conditinal']['lema_createdby_image']['url']; ?>"
                                     alt="createdby">
                                <div class="title_img_content"><?php echo $model->__data['created_by_conditinal']['lema_createdby_info']; ?></div>
                            </div>
							<?php
							//course block
							?>
                            <div class="created_row">
                                <div class="created_by_course">
                                    <div class="title_course"><?php echo count( $course_list ) . __( ' course', 'lema' ); ?></div>
                                    <div class="title_course_content"><?php echo $model->__data['created_by_conditinal']['lema_createdby_course']; ?></div>
                                </div>
                                <div class="created_by_course">
                                    <div class="project"><?php echo __( 'Projects', 'lema' ); ?></div>
                                    <div class="title_course_content"><?php echo $model->__data['created_by_conditinal']['lema_createdby_project']; ?></div>
                                </div>
                                <div class="created_by_course">
                                    <div class="certificate"><?php echo __( 'Certificate', 'lema' ); ?></div>
                                    <div class="title_course_content"><?php echo $model->__data['created_by_conditinal']['lema_createdby_certificate']; ?></div>
                                </div>

                            </div>
                        </div>
					<?php endif; ?>
                    <div class="lema-row lema-row-project">
                        <div class="project_row">
                            <div class="title"><?php echo __( 'Projects Overview', 'lema' ); ?></div>
							<?php echo apply_filters( 'the_content', $model->__data['lema_project_area'] ); ?>
                        </div>
                    </div>
                    <div class="lema-row lema-row-course" id="lema-row-course">
                        <div class="courses_row">
                            <div class="title"><?php echo __( 'Courses', 'lema' ); ?></div>

                            <div class="course_row_content">

								<?php

								if ( ! empty( $course_list ) ) {
									foreach ( $course_list as $index => $course ) {
										echo( '<div class="course_row_content-item">' );

										$shortcode = do_shortcode( '[lema_course_detail style="style-2" courseID="' . $course . '"]' );

										$course = \lema\models\CourseModel::findOne( $course );
										if ( ! empty( $course ) ) {
											printf( $html_format['course_bundle'],
												esc_url( get_permalink( $course->post->ID ) ),
												esc_html( $course->post->post_title ),
												__( 'Commitment', 'lema' ),
												esc_html( $course->post->course_length . ',' . $course->post->course_length ),
												__( 'Subtitle', 'lema' ),
												$course->post->course_language,
												__( 'About the course', 'lema' ),
												$course->post->course_description . '<a href="javascript:void(0)" class="view-more">' . __( "VIEW MORE", "lema" ) . '</a>',
												$shortcode
											);
										}
										echo( '</div>' );
									}
								}
								?>

                            </div>

                        </div>
                    </div>
					<?php if ( $model->__data['creator_by_conditinal']['enabled'] === 'on' ): ?>
                        <div class="lema-row lema-row-creator" id="lema-row-creator">
                            <div class="creator_row">
                                <div class="title"><?php echo __( 'Creators', 'lema' ); ?></div>
                                <div class="background-image">
                                    <div class="title">
										<?php echo $model->__data['creator_by_conditinal']['lema_creator_info_text']; ?>
                                    </div>
                                    <div class="description">
										<?php echo $model->__data['creator_by_conditinal']['lema_creator_description_area']; ?>
                                    </div>

                                </div>
                            </div>
                        </div>
					<?php endif; ?>
					<?php if ( is_array( $model->__data['lema_faq_repeator'] ) && count( $model->__data['lema_faq_repeator'] ) > 0 ): ?>

						<?php
						$faq_attrs = array();
						foreach ( $model->__data['lema_faq_repeator'] as $val ) {
							$oject       = (object) array(
								'title'   => $val['lema_faq_title_text'],
								'content' => $val['lema_faq_des_area']
							);
							$faq_attrs[] = $oject;
						}
						?>
                        <div class="lema-row lema-row-faq" id="lema-row-faq">
                            <div class="faq_row">
                                <div class="title"><?php echo __( 'FAQs', 'lema' ); ?></div>
								<?php
								echo do_shortcode(
									'[slz_accordion style="style-2" icon="arrow" option_show="option-2" accordion_list="' . urlencode( json_encode( $faq_attrs ) ) . '" style_layout_1="st-california"]'
								);
								?>
                            </div>
                        </div>
					<?php endif; ?>
                </div>

            </div>

        </div>
    </div>
</div>

<?php $custom_css = '';
if ( isset( $model->__data['creator_by_conditinal']['lema_creator_image']['url'] ) && ! empty( $model->__data['creator_by_conditinal']['lema_creator_image']['url'] ) ) {
	$custom_css .= '
	.lema-row .creator_row .background-image
	{background-image: url("' . $model->__data['creator_by_conditinal']['lema_creator_image']['url'] . '");
    background-position: unset;
    background-repeat:  no-repeat;
    background-size:  auto;}
';
}
if ( ! empty( $custom_css ) ) {
	do_action( 'slz_add_inline_style', $custom_css );
}


?>



