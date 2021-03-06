<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 */

 ?>
<div class="lema-course-authors">
    <?php
    do_action('lema_pre_shortcode_instructor', $_params_);
    foreach( $list_instructor as $key => $instructor ){
        if (empty($instructor->display_name)){
            $instructor->display_name = $instructor->user_login;
        }
        $avatar = get_avatar($instructor->ID);

        $instructor_url = get_author_posts_url($instructor->ID,$instructor->user_nicename);

        if($data['layout'] == 'card'){ ?>
            <!-- render html instructor in item card -->
            <div class="lema-author">
                <a href="<?php echo esc_url($instructor_url) ?>" class="image-thumb" title="<?php echo esc_html($instructor->display_name) ?>">

                    <?php
                        if ($data['show_image']){
                            echo $avatar;
                        }
                    ?>

                    <?php if ($data['show_name']): ?>
                        <span><?php echo esc_html($instructor->display_name) ?></span>
                    <?php endif; ?>
                </a>
            </div>

        <?php }else{ ?>

            <!-- render html instructor in course detail -->
            <section class="intructor">
                <div class="intructor-wrapper">
                    <div class="intructor-info">

                        <?php if($data['show_image'] || $data['show_statistic']){ ?>
                            <div class="intructor-info-left">
                                <?php if($data['show_image']){ ?>
                                    <div class="img-info">
                                        <?php echo $avatar?>
                                    </div>
                                <?php } ?>

                                <?php if($data['show_statistic']){ ?>
                                    <div class="detail-intructor-info">
                                        <?php foreach($instructor->statistic as $statistic ){ ?>
                                            <div class="item"><?php echo esc_html($statistic['value']);?> <span><?php echo esc_html($statistic['title']) ?></span></div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <div class="intructor-info-right">

                            <?php if($data['show_icon_avatar']){ ?>
                                <div class="avatar-icon">
                                    <?php echo get_avatar($instructor->ID)?>
                                </div>
                            <?php } ?>

                            <?php if($data['show_name']){ ?>
                                <div class="intructor-title"><a href="<?php echo esc_url($instructor_url) ?>"><?php echo esc_html($instructor->display_name) ?></a></div>
                            <?php } ?>

                            <?php if($data['show_role']) { ?>
                                <div class="intructor-position"><?php echo esc_html__($instructor->job_title, 'lema') ?></div>
                            <?php } ?>

                            <?php if($data['show_social']): ?>
                                <div class="intructor-social social">
                                    <?php foreach(\lema\models\UserModel::listSocial() as $key=>$social):
                                        if($instructor->$key !== ''){
                                            ?>
                                            <a href="<?php echo esc_html($instructor->$key)?>">
                                                <i class="fa <?php echo $social['icon']?>"></i>
                                            </a>
                                        <?php } ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <?php if($data['show_description']){ ?>
                                <div class="intructor-description">
                                    <?php echo apply_filters('the_content', $instructor->description) ?>
                                </div>
                            <?php } ?>

                            <?php if($data['show_course_popular'] && count($instructor->list_courses)){ ?>
                                <div class="popular-courses">
                                    <div class="popular-course-main-title"><?php echo esc_html__('Popular courses', 'lema') ?></div>
                                    <ul class="popular-course-list">
                                        <?php foreach($instructor->list_courses as $key => $course){?>
                                            <li class="popular-course-item">
                                                <?php
                                                $link  = get_permalink($course->ID) ;
                                                $link = apply_filters('lema_course_link', $link, $course->ID);
                                                ?>
                                                <a href="<?php echo $link?>">
                                                    <div class="popular-course-image">
                                                        <img src="<?php echo get_the_post_thumbnail_url($course->ID)?>">
                                                    </div>
                                                    <div class="popular-course-title"><?php echo esc_html($course->post_title) ?></div>
                                                </a>
                                            </li>
                                            <?php if($key == 1) break; } ?>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </section>

            <?php
        }
    }
    do_action('lema_suf_shortcode_instructor', $_params_);
    ?>
</div>
