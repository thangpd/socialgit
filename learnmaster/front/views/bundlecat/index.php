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
    <h2 class="lema-course-list-title"><?php echo $term->name ?></h2>

	<?php

	$cat_content_header = slz_get_db_term_option( $term->term_id, $term->taxonomy, 'bundle_editor_cat', '' );
	do_action( 'slz_the_theme_process_vc', $cat_content_header );
	echo apply_filters( 'the_content', $cat_content_header );


	$html_format = array(
		'bundle_html' => '<ul class="bundle_list">

		<li class="bundle-expand"><i class="fa fa-angle-down"></i></li>
		<li class="bundle">
			<div class="course_option">
				<div class="btn_enroll">%5$s</div>
				<div class="option_3_splash">
					<i class="icon ion-android-more-vertical"></i>
					<div class="hide_bundle_list">
						<div class="hide-course-info">
							<a>%6$s</a>
						</div>
						<div class="see-course-info">
							<a>%7$s</a>
						</div>
					</div>
				</div>	
			</div>
            <div class="title"><a href="%2$s">%1$s</a></div>
            <div class="except_bundle">%3$s</div>
			<div class="course_list">%4$s</div>
        </li>
    </ul>',
		'course_html' => '<ul class="course_list_bundle">
        <li class="course">
            <div class="title"><a href="%2$s">%1$s</a></div> <div class="hour_total">%3$s</div>
            <div class="except_course">%4$s</div>
        </li>
    </ul>',

	);


	$bundles = $context->getBundleOfCat( $term );

	foreach ( $bundles as $val ) :
		$title = ! empty( $val->post_title ) ? $val->post_title : __( 'Bundle' );

		$courses     = \lema\models\BundleModel::get_list_course_bundle( $val->ID );
		$course_html = '';

		if ( ! empty( $courses ) ):
			foreach ( $courses as $index => $course ) {
				$course = \lema\models\CourseModel::findOne( $course );
				if ( ! empty( $course ) ) {
					$course_html .= sprintf( $html_format['course_html'],
						__( 'Course ' ) . ++ $index . _( ' : ' ) . $course->post->post_title,
						get_permalink( $course->post->ID ),
						$course->__data['course_effort'],
						$course->__data['course_description'] );
				}
			}
		endif;
//		'post_type'   => '',
//			'expire_date' => '1',//expired in number month.
//			'post_id'     => 0,
//			'price'       => 10,
//		do_shortcode('[lema_checkout_bundle]')
		$price = get_post_meta( $val->ID, 'lema_sale_price', true );

		printf( $html_format['bundle_html'], $title, get_permalink( $val->ID ), $val->post_excerpt, $course_html, do_shortcode( '[lema_checkout_bundle post_id=' . $val->ID . ' price=' . $price . ' post_type="' . $val->post_type . '"]' ), __( 'Hide course info', 'lema' ), __( 'See this course', 'lema' ) );

	endforeach;
	?>

</div>