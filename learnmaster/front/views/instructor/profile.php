<?php
/**
 *
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 * @var \lema\models\Instructor $instructor
 */

$slz_container_css = slz_get_db_settings_option( 'intructor-ac-sidebar-layout' );


$show_sidebar    = false;
$sidebar_options = 'intructor-ac-sidebar-layout';
$sidebar         = 'instructor-sidebar';
$sidebar_class   = 'none';
$sidebar_layout  = slz_get_db_settings_option( $sidebar_options, 'left' );
// layout
if ( $slz_container_css != 'none' ) {
	$sidebar_layout_class = 'slz-sidebar-' . $sidebar_layout;
	$content_class        = 'col-md-8 col-sm-12 col-xs-12';
	$sidebar_class        = 'col-md-4 col-sm-12 col-xs-12';
	$show_sidebar         = true;
} else {
	$content_class = 'col-md-12 col-sm-12 col-xs-12';
}

$slz_container_css = array(
	'show_sidebar'         => $show_sidebar,
	'sidebar_layout_class' => $sidebar_layout_class,
	'sidebar_class'        => $sidebar_class,
	'content_class'        => $content_class,
	'sidebar_layout'       => $sidebar_layout,
	'sidebar'              => $sidebar,
);
?>


<div class="lema-page-content lema-instructor">
    <div class="container">
        <div class="slz-blog-detail slz-partner-detail <?php echo esc_attr( $slz_container_css['sidebar_layout_class'] ); ?>">
            <div class="row">

                <div class="slz-content-column <?php echo esc_attr( $slz_container_css['content_class'] ); ?>">
                    <!-- User Profile section-->
                    <div class="lema-user-profile">
                        <div class="title">
							<?php echo __( 'Introduction', 'lema' ); ?>
                        </div>
                        <div class="block-wrapper">

                            <div class="img-wrapper">
								<?php echo get_avatar( $instructor->user->ID, 500 ); ?>
                            </div>
                            <div class="content-wrapper">
                                <div class="name">
                                    <a href="<?php

									$instructor_user = new WP_User( $instructor->user->ID );
									echo \lema\models\UserModel::gotoProfileStatic( '', $instructor_user ) ?>"><?php the_author_meta( 'display_name', $instructor->user->ID ); ?></a>
									<?php if ( is_user_logged_in() && get_current_user_id() == $instructor->user->ID ): ?>
                                        <a href="<?php echo lema()->page->getPageUrl( lema()->config->getUrlConfigs( 'lema-instructor-edit-profile' ) ) ?>"><i
                                                    class="fa fa-edit"></i></a>
									<?php endif; ?>
                                </div>

								<?php if ( $position = get_user_meta( $instructor->user->ID, 'job_title', true ) ) : ?>
                                    <div class="position">
										<?php
										the_author_meta( 'job_title', $instructor->user->ID );
										?>
                                    </div>
								<?php endif; ?>

                                <div class="social">
                                    <ul class="list-wrapper">
										<?php

										$link_user = get_the_author_meta( 'user_url', $instructor->user->ID );
										if ( $link_user !== '' ) { ?>
                                            <li>
                                                <a href="<?php echo $link_user; ?>" class="link">
                                                    <i class="fa fa-globe"></i>
                                                </a>
                                            </li>
										<?php } ?>
										<?php foreach ( \lema\models\UserModel::listSocial() as $key => $social ) {
											$link = get_the_author_meta( $key, $instructor->user->ID );
											if ( $link !== '' ) {
												?>
                                                <li>
                                                    <a href="<?php echo $link; ?>" class="link">
                                                        <i class="icon fa <?php echo $social['icon'] ?>"></i>
                                                    </a>
                                                </li>
											<?php }
										} ?>
                                    </ul>

                                </div>
                            </div>
                        </div>
                        <!-- End of User Profile section-->
                        <!-- Detail -->
                        <div class="content-text">
							<?php echo apply_filters( 'the_content', get_user_meta( $instructor->user->ID, 'instructor_description', true ) ); ?>
                        </div>
                        <div class="content-text">
							<?php echo apply_filters( 'the_content', get_user_meta( $instructor->user->ID, 'description', true ) ); ?>
                        </div>
						<?php
						/*
						 * SC progress bar
							'title'               => '',
							'percent'             => '',
							'progress_bar_color'  => '',
							'title_color'         => '',
							'percent_color'       => '',
							'marker_color'        => '',*/

						$link_user                  = get_the_author_meta( 'user_url', $instructor->user->ID );
						$attrs_progress_bar_default = array(
							'title'              => '',
							'percent'            => '',
							'progress_bar_color' => '#07d79b',
							'title_color'        => '#868686',
							'percent_color'      => '#868686'
						);

						$atts_progress = array();
						foreach ( \lema\models\UserModel::listSkills() as $key => $skill ) {
							$percent = get_the_author_meta( $key, $instructor->user->ID );
							if ( $percent !== '' ) {
								$arr             = array( 'title' => $skill['title'], 'percent' => $percent );
								$atts_progress[] = array_merge( $attrs_progress_bar_default, $arr );
							}
						}
						//my skill
						if ( ! empty( $atts_progress ) ) :
							?>
                            <div class="title">
								<?php echo __( 'My skills', 'lema' ); ?>
                            </div>
                            <div class="skills">
								<?php echo do_shortcode( '[slz_progress_bar progress_bar_list_1="' . urlencode( json_encode( $atts_progress ) ) . '"]' ); ?>
                            </div>
						<?php endif; ?>

                        <!-- my goals-->
						<?php $mygoal = get_user_meta( $instructor->user->ID, 'instructor_goals', true );

						if ( ! empty( $mygoal ) ):
							?>

                            <div class="title">
								<?php echo __( 'My goals', 'lema' ); ?>
                            </div>
                            <div class="summary-text">
								<?php echo apply_filters( 'the_content', $mygoal ); ?>
                            </div>
						<?php endif; ?>
                        <!-- User Profile -->

                    </div>

                </div>

				<?php if ( $slz_container_css['show_sidebar'] ) : ?>
                    <div class="slz-sidebar-column slz-widgets <?php echo esc_attr( $slz_container_css['sidebar_class'] ); ?>">
						<?php slz_extra_get_sidebar( $slz_container_css['sidebar'] ); ?>
                    </div>
				<?php endif; ?>

                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
