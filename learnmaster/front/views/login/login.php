<?php
$login_action = admin_url( 'admin-ajax.php?action=lema_login' );

//get redirect_to= ;
   $login_action.='&'.'redirect_to='.$redirect_to;

   $settings=slz_get_db_settings_option();
   //get logo site
    $logo_url = slz_akg( 'logo/url', $settings, '' );
?>
<div class="wrapper-login-panel slz-change-color">
<div class="login-panel">

    <div class="login-title">
        <a href="<?php echo esc_url( home_url() ); ?>" class="login-title">
		    <?php
		    if ( isset( $logo_url ) && ! empty( $logo_url ) ) {
			    ?><img src="<?php echo esc_url( $logo_url ); ?>"><?php
		    } else {
			    echo esc_html(slz_akg( 'logo-text', $settings, '' ) );
		    }; ?>
        </a>
        <div class="login-subtitle"> <?php echo esc_html__('Universal access to the world’s best education .','lema') ?></div>
    </div>
    <div class="slz-login-register-modal">
        <ul class="nav nav-tabs">
            <li class="tab-login active"><a data-toggle="tab" href="#login-pane"><?php echo esc_html__("Login","lema"); ?></a></li>
            <li class="tab-register"><a data-toggle="tab" href="#register-pane"> <?php echo esc_html__("Register","lema"); ?></a></li>
        </ul>
        <div class="tab-content">
            <div id="login-pane" class="tab-pane tab-pane-login fade in active">
                <!-- <div class="btn-wrapper">
					 <i class="icons fa fa-google-plus"></i>
					 <button class="login-btn btn-google"> CONNECT WITH GOOGLE</button>
				 </div>
				 <div class="btn-wrapper">
					 <i class="icons fa fa-facebook"></i>
					 <button class="login-btn btn-facebook"> CONNECT WITH FACEBOOK</button>
				 </div>
				 <p class="or">or</p>-->
                <form class="login-register-form"
                      action="<?php echo esc_url($login_action); ?>" method="post">
                    <p class="text-danger message-result"></p>
                    <div class="input-wrapper">
                        <div class="login-email">
                        <input class="form-control" required type="text" name="email"
                               placeholder="<?php echo esc_html__( 'Email' , 'lema'); ?>">
                        </div>
                    </div>
                    <div class="input-wrapper">
                        <div class="login-password">
                        <input class="form-control" required type="password" name="password" placeholder="<?php echo esc_html__( 'Password' , 'lema'); ?>">
                        </div>
                    </div>
                    <div class="input-wrapper">
                        <div class="login-button">
                        <button class="form-control btn-submit" type="submit" name="login">
	                        <?php echo esc_html__( 'LOGIN' , 'lema'); ?>
                        </button>
                        </div>
                    </div>
                </form>
                <div class="login-register-help">
                    <div class="forgot-pass"><a class="link" href="<?php echo wp_lostpassword_url(); ?>"> <?php echo esc_html__("FORGOT MY PASSWORD ","lema"); ?></a></div>
                    <div class="if-register">
                        <span class="text"> <?php echo esc_html__("Don't have an account?","lema"); ?></span>
                        <a class="link btn-register" href="#register-pane"><?php echo esc_html__("REGISTER","lema"); ?></a>
                    </div>
                </div>
            </div>
            <div id="register-pane" class="tab-pane tab-pane-register fade">
                <!--  <div class="btn-wrapper">
					  <i class="icons fa fa-google-plus"></i>
					  <button class="register-btn btn-google">CONNECT WITH GOOGLE</button>
				  </div>
				  <div class="btn-wrapper">
					  <i class="icons fa fa-facebook"></i>
					  <button class="register-btn btn-facebook">CONNECT WITH FACEBOOK</button>
				  </div>
				  <p class="or">or</p>-->
                <form class="login-register-form"
                      action="<?php echo admin_url( 'admin-ajax.php?action=lema_register' ); ?>" method="post">
                    <p class="text-danger message-result"></p>
                    <div class="input-wrapper">
                        <div class="regis-name">
                        <input class="form-control" type="text" name="display_name" required placeholder="<?php echo esc_html__( 'Username' , 'lema'); ?>">
                        </div>
                    </div>
                    <div class="input-wrapper">
                        <div class="regis-email">
                        <input class="form-control email-validate" required type="text" name="email"
                               placeholder="<?php echo esc_html__("Email","lema"); ?>">
                        </div>
                    </div>
                    <!--<div class="input-wrapper">
                        <input class="form-control" type="password" name="pass" placeholder="Password">
                    </div>-->
                    <div class="input-wrapper">
                        <button class="form-control btn-submit" type="submit" name="login">
	                        <?php echo esc_html__("REGISTER","lema"); ?>
                        </button>
                    </div>
                </form>
                <div class="login-register-help">
                    <div class="had-account">
                        <span class="text"><?php echo esc_html__("I have an account!",'lema'); ?></span>
                        <a class="link btn-login" href="#login-pane"><?php echo esc_html__("LOGIN","lema"); ?></a>
                    </div>
                    <div class="accepted-note">
                        <p class="description">
       <?php echo esc_html__("By signing up to create an account I accept Education’s ","lema"); ?>
                            <a class="link" href="#"><?php echo esc_html__("Terms of Use","lema"); ?></a> <?php echo esc_html__("and","lema"); ?> <a class="link" href="#"><?php echo esc_html__( 'Privacy Policy' , 'lema'); ?></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>