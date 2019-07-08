<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
?>
<div class="lema-user-profile-page">
    <div class="lema-page-content">
        <div class="container">
            <h2 class="lema-user-title"><?php echo __('Edit Profile','lema'); ?></h2>
			<?php
            echo $context->render( 'profile', [ 'user' => $user ] ); ?>
            <div class="lema-tabs lema-user-profile-tabs">
                <div class="block-wrapper">
                    <div class="tab-list-wrapper">
                        <ul class="tab-list">
                            <li><a data-tab="info"
                                   class="tab-link active"><?php echo esc_html__( 'Profile Info', 'education' ); ?></a>
                            </li>
                            <!-- <li><a data-tab="photo" class="tab-link">Photo</a></li> -->
                            <li><a data-tab="account"
                                   class="tab-link"><?php echo esc_html__( 'Account', 'education' ); ?></a></li>
                            <!-- <li><a data-tab="4" class="tab-link">Creadit Cards</a></li>
							<li><a data-tab="5" class="tab-link">Privacy</a></li>
							<li><a data-tab="6" class="tab-link">Notification</a></li> -->
                        </ul>
                    </div>

                    <div class="tab-content-wrapper">
                        <div class="tab-panel active" data-content="info">
                            <form class=" lema-ajax-form user-profile-editing-form" method="post"
                                  enctype="multipart/form-data">
                                <input type="hidden" name="action" value="ajax_update_profile">
                                <div class="user-profile-editing">
                                    <div class="block-wrapper">
                                        <div class="lema-columns lema-column-2">
                                            <div class="item item-profile">
                                                <div class="block-wrapper">
                                                    <div class="title">
														<?php echo esc_html__( 'Basic Information', 'education' ); ?>
                                                        <span class="red-star">*</span>
                                                    </div>
                                                    <div class="edit-form-wrapper">
														<?php foreach ( $context->listInfo() as $key => $info ) { ?>
                                                            <div class="lema-form-group lema-input-wrapper">
                                                                <input type="text"
                                                                       class="lema-form-control character-input-limit"
                                                                       placeholder="Your <?php echo $info ?>"
                                                                       name="<?php echo $key ?>"
                                                                       value="<?php the_author_meta( $key, $user->ID ) ?>"
                                                                       maxlength="60" minlength="0">
                                                                <div class="character-limit">60</div>
                                                            </div>
														<?php } ?>

														<?php foreach ( \lema\models\UserModel::listMeta() as $key => $info ) { ?>
                                                            <div class="lema-form-group lema-input-wrapper">
                                                                <input type="text"
                                                                       class="lema-form-control character-input-limit"
                                                                       placeholder="Your <?php echo $info ?>"
                                                                       name="meta[<?php echo $key ?>]"
                                                                       value="<?php the_author_meta( $key, $user->ID ) ?>"
                                                                       maxlength="60" minlength="0">
                                                                <div class="character-limit">60</div>
                                                            </div>
														<?php } ?>

                                                    </div>
                                                </div>
                                                <div class="block-wrapper">
                                                    <div class="title">
                                                        Biography
                                                    </div>
                                                    <div class="edit-form-wrapper">
                                                        <div class="lema-form-group">
                                                            <textarea class="lema-form-control textarea-input"
                                                                      placeholder="<?php echo esc_html__( 'Introduce about yourself', 'lema' ) ?>"
                                                                      name="description"><?php the_author_meta( 'description', $user->ID ); ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="block-wrapper">
                                                    <div class="title">
                                                        Links
                                                    </div>
                                                    <div class="edit-form-wrapper">
                                                        <div class="lema-form-group">
                                                            <input placeholder="Your website link" type="text"
                                                                   class="lema-form-control" name="user_url"
                                                                   value="<?php the_author_meta( 'user_url', $user->ID ) ?>">
                                                        </div>
														<?php foreach ( \lema\models\UserModel::listSocial() as $key => $social ) { ?>
                                                            <div class="lema-form-group">
                                                                <input placeholder="Your <?php echo $social['title'] ?> link"
                                                                       type="text" class="lema-form-control"
                                                                       name="meta[<?php echo $key ?>]"
                                                                       value="<?php echo $user->$key ?>">
                                                            </div>
														<?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="block-wrapper">
                                    <div class="lema-form-group text-c">
                                        <button type="submit" class="button button-save"><i class="fa fa-save"></i> Save
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-panel" data-content="account">
                            <center><b><span id="lema-res-message"></span></b></center>
                            <form class="user-change-password-form lema-ajax-form" method="post"
                                  enctype="multipart/form-data">
                                <input type="hidden" name="action" value="ajax_update_profile">
                                <div class="user-profile-editing">
                                    <div class="block-wrapper">
                                        <div class="lema-columns lema-column-2">
                                            <div class="item item-profile">
                                                <div class="block-wrapper">
                                                    <div class="title">
														<?php echo esc_html__( 'Change your password', 'education' ); ?>
                                                    </div>
                                                    <div class="edit-form-wrapper">
                                                        <div class="lema-form-group">
                                                            <input placeholder="* <?php echo esc_html__( 'Old password', 'education' ) ?>"
                                                                   type="password" class="lema-form-control" required
                                                                   name="password[old]" value="">
                                                        </div>

                                                        <div class="lema-form-group">
                                                            <input placeholder="* <?php echo esc_html__( 'New password', 'education' ) ?>"
                                                                   type="password" class="lema-form-control" required
                                                                   name="password[new]" value="">
                                                        </div>

                                                        <div class="lema-form-group">
                                                            <input placeholder="* <?php echo esc_html__( 'Confirm new password', 'education' ) ?>"
                                                                   type="password" class="lema-form-control" required
                                                                   name="password[confirm]" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="block-wrapper">
                                    <div class="lema-form-group text-c">
                                        <button type="submit" class="button button-save"><i class="fa fa-save"></i> Save
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End tab -->
        </div>
    </div>
</div>
