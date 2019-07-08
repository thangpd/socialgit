<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
?>
<input type="hidden" name="lema-courseId" value="<?php echo $courseModel->post->ID ?>">

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
                        <div class="course_detail course_detail_hide" id="course_detail">
                            %9$s
                        </div>',
	'course_sub_bundle' => '<div class="course_sub">
                    <div class="title"><a href="%1$s">%2$s</a></div>
                    %3$s
                </div>',

);

$pre = 'lema_course_';
?>
<div class="lema-page-content bundle-detail">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="lema-sidebar-anchor" id="lema-sidebar-anchor">
                    <div class="about scroll">
                        <a href="#course-single-about"><?php echo __( 'About', 'lema' ); ?></a>
                    </div>
                    <div class="course">
                        <a href="#course-single-course"><?php echo __( 'Course', 'lema' ); ?></a>
                    </div>
                    <div class="creators">
                        <a href="#course-single-creator"><?php echo __( 'Creators', 'lema' ); ?></a>
                    </div>
                    <div class="faq">
                        <a href="#course-single-faq"><?php echo __( 'FAQ', 'lema' ); ?></a>
                    </div>
                    <div class="title">
						<?php echo esc_html( $courseModel->post->post_title ) ?>
                    </div>
					<?php if ( isset( $courseId ) && ! empty( $courseId ) ) {
						$classButton = 'button continue- lesson lema - btn lema - btn - primary';
						?>
                        <div class="lema-course-button">
                            <a class="<?php echo esc_attr( $classButton ) ?>"
                               href="<?php echo esc_url( $linkCourse ) ?>"><?php echo esc_html( $courseButtonText ) ?>
                            </a>
                        </div>
						<?php
					} ?>
                </div>
            </div>
            <div class="col-lg-9 col-md-9">
                <div class="lema-content-right">
                    <div class="lema-row lema-row-title">
                        <div class="title_bundle">
							<?php echo esc_html( $courseModel->post->post_title ) ?>
                            <a
                                    href="<?php echo esc_url( get_permalink( $courseModel->post->ID ) ); ?>"></a>
                        </div>
                        <div class="subtitle_bundle"><?php echo esc_html( $courseModel->__data['course_subtitle'] ); ?></div>
                    </div>
                    <div class="lema-row lema-row-about">
                        <div class="about_row" id="course-single-about">
                            <div class="title"><?php echo __( 'About this Specialization', 'lema' ); ?></div>
							<?php echo apply_filters( 'the_content', $courseModel->__data[ $pre . 'about_area' ] ); ?>
                        </div>
                    </div>
					<?php if ( isset( $courseModel->__data[ $pre . 'created_by_conditinal' ]['enabled'] ) && $courseModel->__data[ $pre . 'created_by_conditinal' ]['enabled'] === 'on' ):
						?>
                        <div class="lema-row lema-row-created">
                            <div class="created_row">
                                <div class="title"><?php echo __( 'Created By', 'lema' ); ?></div>
                                <img src="<?php echo $courseModel->__data[ $pre . 'created_by_conditinal' ][ $pre . 'createdby_image' ]['url']; ?>"
                                     alt="createdby">
                                <div class="img_content"><?php echo $courseModel->__data[ $pre . 'created_by_conditinal' ][ $pre . 'createdby_info' ]; ?></div>
								<?php
								//course block
								?>
                                <div class="created_by_course-list">
                                    <div class="created_by_course">
                                        <div class="title_course"><?php if ( is_array( $course_list ) || is_object( $course_list ) ) {
												echo count( $course_list ) . __( 'course', 'lema' );
											} ?></div>
										<?php echo $courseModel->__data[ $pre . 'created_by_conditinal' ][ $pre . 'createdby_course' ]; ?>
                                    </div>
                                    <div class="created_by_course">
                                        <div class="project"><?php echo __( 'Projects', 'lema' ); ?></div>
										<?php echo $courseModel->__data[ $pre . 'created_by_conditinal' ][ $pre . 'createdby_project' ]; ?>
                                    </div>
                                    <div class="created_by_course">
                                        <div class="certificate"><?php echo __( 'Certificate', 'lema' ); ?></div>
										<?php echo $courseModel->__data[ $pre . 'created_by_conditinal' ][ $pre . 'createdby_certificate' ]; ?>
                                    </div>
                                </div>


                            </div>
                        </div>
					<?php endif; ?>
                    <div class="lema-row lema-row-project">
                        <div class="project_row">
                            <div class="title"><?php echo __( 'Projects Overview', 'lema' ); ?></div>
							<?php echo apply_filters( 'the_content', $courseModel->__data[ $pre . 'project_area' ] ); ?>
                        </div>
                    </div>
                    <div class="lema-row lema-row-course" id="course-single-course">
                        <div class="courses_row">
                            <div class="title"><?php echo __( 'About This Course', 'lema' ); ?></div>

                            <div class="course_row_content">
								<?php

								$shortcode = do_shortcode( '[lema_course_detail style = "style-1" courseID = "' . $courseId . '"]' );


								printf( $html_format['course_bundle'],
									esc_url( get_permalink( $courseModel->post->ID ) ),
									esc_html( $courseModel->post->post_title ),
									__( 'Commitment', 'lema' ),
									esc_html( $courseModel->post->course_length . ',' . $courseModel->post->course_length ),
									__( 'Subtitle', 'lema' ),
									$courseModel->post->course_language,
									__( 'About the course', 'lema' ),
									$courseModel->post->course_description,
									$shortcode
								);
								?>
                            </div>
                            <button class="course-single-view-more"> view more</button>
                            <div class="lema-course-button_enroll_now">
                                <a class="<?php echo esc_attr( $classButton ) ?>"
                                   href="<?php echo esc_url( $linkCourse ) ?>"><?php echo esc_html( $courseButtonText ) ?>
                                </a>
                            </div>
                        </div>
                    </div>
					<?php if ( isset( $courseModel->__data[ $pre . 'creator_by_conditinal' ]['enabled'] ) && $courseModel->__data[ $pre . 'creator_by_conditinal' ]['enabled'] === 'on' ): ?>
                        <div class="lema-row lema-row-creator" id="course-single-creator">
                            <div class="creator_row">
                                <div class="title"><?php echo __( 'Creators', 'lema' ); ?></div>
                                <div class="background-image">
                                    <div class="title">
										<?php echo $courseModel->__data[ $pre . 'creator_by_conditinal' ][ $pre . 'creator_info_text' ]; ?>
                                    </div>
                                    <div class="description">
										<?php echo $courseModel->__data[ $pre . 'creator_by_conditinal' ][ $pre . 'creator_description_area' ]; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
						<?php $custom_css = '';
						if ( isset( $courseModel->__data[ $pre . 'creator_by_conditinal' ][ $pre . 'creator_image' ]['url'] ) && ! empty( $courseModel->__data[ $pre . 'creator_by_conditinal' ][ $pre . 'creator_image' ]['url'] ) ) {
							$custom_css .= '
                            .lema-row .creator_row .background-image
                            {background-image: url("' . $courseModel->__data[ $pre . 'creator_by_conditinal' ][ $pre . 'creator_image' ]['url'] . '");
                            background-position: unset;
                            background-repeat:  no-repeat;
                            background-size:  auto;}
                        ';
						}
						if ( ! empty( $custom_css ) ) {
							do_action( 'slz_add_inline_style', $custom_css );
						}


						?>
					<?php endif; ?>
					<?php if ( is_array( $courseModel->__data[ $pre . 'faq_repeator' ] ) && count( $courseModel->__data[ $pre . 'faq_repeator' ] ) > 0 ): ?>

						<?php
						$faq_attrs = array();
						foreach ( $courseModel->__data[ $pre . 'faq_repeator' ] as $val ) {
							$oject       = (object) array(
								'title'   => $val[ $pre . 'faq_title_text' ],
								'content' => $val[ $pre . 'faq_des_area' ]
							);
							$faq_attrs[] = $oject;
						}
						?>
                        <div class="lema-row lema_row_faq" id="course-single-faq">
                            <div class="faq_row">
                                <div class="title"><?php echo __( 'FAQs', 'lema' ); ?></div>
								<?php
								echo do_shortcode(
									'[slz_accordion style = "style-2" icon = "arrow" option_show = "option-2" accordion_list = "' . urlencode( json_encode( $faq_attrs ) ) . '" style_layout_1 = "st-california"]'
								)
								?>
                            </div>
                        </div>
					<?php endif; ?>
					<?php if ( is_array( $courseModel->__data[ $pre . 'howitwork_conditinal' ] )
					           && ! empty( $courseModel->__data[ $pre . 'howitwork_conditinal' ] )
					           && $courseModel->__data[ $pre . 'howitwork_conditinal' ]['enabled'] === 'on' ) : ?>
                        <div class="howitwork">
                            <div class="title"><?php echo __( 'How it works', 'lema' ); ?></div>
                            <ul class="list_hiw">
                                <li>
                                    <div class="title"><?php echo __( 'Course work', 'lema' ); ?></div>
                                    <div class="des"><?php echo esc_html( $courseModel->__data[ $pre . 'howitwork_conditinal' ][ $pre . 'hiw_coursework' ] ) ?></div>
                                </li>
                                <li>
                                    <div class="title"><?php echo __( 'Help from Your Peers', 'lema' ); ?></div>
                                    <div class="des"><?php echo esc_html( $courseModel->__data[ $pre . 'howitwork_conditinal' ][ $pre . 'hiw_help' ] ) ?></div>

                                </li>
                                <li>
                                    <div class="title"><?php echo __( 'Certificates', 'lema' ); ?></div>
                                    <div class="des"><?php echo esc_html( $courseModel->__data[ $pre . 'howitwork_conditinal' ][ $pre . 'hiw_certificate' ] ) ?></div>
                                </li>
                            </ul>
                        </div>
					<?php endif; ?>
                    <div id="Rating" class="course-rating">
						<?php echo lema_do_shortcode( ' [lema_rating object_id = "' . $courseId . '" style = "viewcomment" has_rating=1]' ); ?>
                    </div>
					<?php if ( isset( $courseId ) && ! empty( $courseId ) ) {
						$classButton = 'button continue- lesson lema - btn lema - btn - primary';
						?>
                        <div class="lema-course-button lema-course-button-bottom">
                            <a class="<?php echo esc_attr( $classButton ) ?>"
                               href="<?php echo esc_url( $linkCourse ) ?>"><?php echo esc_html( $courseButtonText ) ?>
                            </a>
                        </div>
						<?php
					} ?>
                </div>
            </div>
        </div>
    </div>
</div>

