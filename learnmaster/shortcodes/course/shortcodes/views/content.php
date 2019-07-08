<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 * @var \lema\shortcodes\course\CourseShortcode $context
 */
$model = lema\models\CourseModel::findOne( $data['post_id'] );
/** @var \lema\models\CourseLevelModel $modelDE */
$modelDE = \lema\models\CourseLevelModel::getInstance();

$course_subtitle = $model->course_subtitle;
$format_date     = get_option( 'format_date' );
$date_public     = get_the_date( $format_date, $data['post_id'] );
$listLevel       = $modelDE->getOptions();

$course_level = '';
if ( isset( $listLevel[ $model->course_level ] ) ) {
	$course_level = $listLevel[ $model->course_level ];
}

$course_description = lema()->helpers->general->limitWords( $model->course_description,
	[ 'limit' => $data['limit_text'], 'afterStr' => '...' ] );
?>
<div class="lema-course-detail-wrapper">
    <div class="lema-course-detail">
        <div class="course-detail-wrapper">
            <div class="course-detail-title"><?php echo esc_html( $model->post_title ) ?></div>

            <div class="course-detail-published"><?php esc_html__( 'Publish:', 'lema' ) ?>
                <span><?php echo esc_html( $date_public ) ?></span></div>
            <div class="course-detail-info">
				<?php if ( ! empty( $course_level ) ): ?>
                    <div class="skill">
                        <i class="icon ion-ios-game-controller-b-outline"></i>
                        <div class="skill"><?php echo esc_html__( 'Skill: ', 'lema' ) ?>
                            <span><?php echo esc_html( $course_level ) ?></span>
                        </div>
                    </div>
				<?php endif; ?>
                <div class="lectures">Lectures: <span>12</span></div>
            </div>

            <div class="course-detail-content"><?php echo apply_filters( 'the_content', $course_description ) ?></div>

            <div class="content-footer">
                <div class="enroll-course">
					<?php
					$show_image = 1;
					if ( educef_checkenrolled_woocommerce( 0, $data['post_id'] ) ) {
						$enroll_button = '<div class="enroll-course"><a href="' . get_permalink( $model->post->ID ) . '">' . __( 'LEARN NOW ', 'lema' ) . '</a></div>';
						$show_image    = 0;
					} else {
						$enroll_translate = __( 'ENROLL THIS COURSE', 'lema' );
						$enroll_button    = lema_do_shortcode( '[lema_add_cart post_id="' . $data['post_id'] . '" title="' . $enroll_translate . '" layout="" show_image="' . $show_image . '"]' );
					}

					$enroll_button = apply_filters( 'lema_course_enroll_button', $enroll_button, $data['post_id'] );
					echo $enroll_button;
					?>
                </div>
            </div>
        </div>
    </div>
</div>