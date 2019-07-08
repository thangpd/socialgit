<?php
define('PLUGIN_URL', plugin_dir_url( __FILE__ ));
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Learning Master</title>
  	
    <link rel="stylesheet" href="<?php echo PLUGIN_URL ?>/assets/libs/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?php echo PLUGIN_URL ?>/assets/libs/jquery-ui-1.11.4/jquery-ui.min.css">
     <link rel="stylesheet" href="<?php echo PLUGIN_URL ?>/assets/fonts/icon-font/flaticon.css">
    <link rel="stylesheet" href="<?php echo PLUGIN_URL ?>/assets/css/setup-wizard.css">
</head>
<body>
	<div class="la-setup">
		<h1 class="la-logo">
			<a href="#" class="link">
				<img src="<?php echo PLUGIN_URL ?>/assets/images/example-logo.png" alt="" class="la-pic" />
			</a>
		</h1>
		<div class="well" >
		    <div class="wizard">
		    	<div class="rb-logo-bar">
		    		<img src="<?php echo PLUGIN_URL ?>/assets/images/example-logo.png" alt="" class="rb-pic">
		    	</div>
		        <div class="wizard-row steps">
		        	<div class="wizard-step active">
		        		<span class="step">Step 0</span>
		                <p>Welcome to Setup</p>
		                <a href="#step-1" type="button" class="btn btn-primary btn-circle"><span>0</span></a>
		            </div>
		            <div class="wizard-step">
		            	<span class="step">Step 1</span>
		                <p>System check</p>
		                <a href="#step-2" type="button" class="btn btn-default btn-circle"><span>1</span></a>
		            </div>
		            <div class="wizard-step">
		            	<span class="step">Step 2</span>
		                <p>Demo Select</p>
		                <a href="#step-3" type="button" class="btn btn-default btn-circle disabled"><span>2</span></a>
		            </div>
		            <div class="wizard-step">
		            	<span class="step">Step 3</span>
		                <p>Feature Configuration</p>
		                <a href="#step-4" type="button" class="btn btn-default btn-circle disabled"><span>3</span></a>
		            </div>
		            <div class="wizard-step">
		            	<span class="step">Step 4</span>
		                <p>Last check</p>
		                <a href="#step-5" type="button" class="btn btn-default btn-circle disabled"><span>4</span></a>
		            </div>
		             <div class="wizard-step">
		             	<span class="step">Step 5</span>
		                <p>Ready!</p>
		                <a href="#step-6" type="button" class="btn btn-default btn-circle disabled"><span>5</span></a>
		            </div>
		        </div>
		        <div class="button-bar text-right">
		        	<button data-step="step-1" class="button button-secondary flat button-next" type="button">Not right now</button>
                	<button data-step="step-1" class="button button-tertiary flat button-next" type="button">Continue</button>
		        </div>
		    </div>
		    <fieldset>
		        <form role="form" action="<?php echo esc_url(get_admin_url()) ?>" method="post">
		        	<div class="step-content" id="step-1">
	                    <div class="form-group">
	                        <h1 class="wz-title">RubikThemes Setup Wizard</h1>
	                        <h4>Welcome to the setup wizard for RubikThemes Theme. You're using RubikThemes theme.</h4>
	                        <p>Thank you for choosing the RubikThemes from ThemeForest. This quick setup wizard will help you configure your new website. This wizard will install the required WordPress plugins, default content, logo and tell you a little about Help &amp; Support options. It should only take 5 minutes.</p>
	                        <p>No time right now? If you don't want to go through the wizard, you can skip and return to the WordPress dashboard. Come back anytime if you change your mind!</p>
	                    </div>
	                    <div class="la-form-group  text-right hidden-bar">
	                    	<button class="button button-secondary flat nextBtn" type="button">Not right now</button>
	                    	<button class="button button-tertiary flat nextBtn" type="button">Let's Go!</button>
	                    </div>
		            </div>
		            <div class="step-content" id="step-2">
	                    <div class="form-group">
	                        <h1 class="wz-title">System check</h1>
	                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</p>
	                        <div class="rb-tip">
	                        	<span class="approved"><i class="flaticon-checked-1"></i> Meets minimum requirements</span>
	                        	<span class="improvement"><i class="flaticon-exclamation"></i> We have some improvements to suggest</span>
	                        </div>
	                       	<div class="rb-configuration-check-st">
	                       		<h3 class="rb-title">WordPress Environment</h3>
	                       		<div class="rb-configuration-list">
	                       			<div class="item-group">
		                       			<div class="item" data-status="approved">
		                       				<div class="icon-status"></div>
		                       				<div class="rb-configuration-content">
		                       					<h4 class="title">Home URL</h4>
		                       					<div class="desc">
		                       						<strong>http://wp.solazu.net/example/</strong>
		                       						<span>The URL of your site's homepage</span>
		                       					</div>
		                       				</div>
		                       			</div>
		                       			<div class="item" data-status="approved">
		                       				<div class="icon-status"></div>
		                       				<div class="rb-configuration-content">
		                       					<h4 class="title">Site URL</h4>
		                       					<div class="desc">
		                       						<strong>http://wp.solazu.net/example/</strong>
		                       						<span>The root URL of your site</span>
		                       					</div>
		                       				</div>
		                       			</div>
	                       			</div>
	                       			<div class="item-group">
		                       			<div class="item" data-status="approved">
		                       				<div class="icon-status"></div>
		                       				<div class="rb-configuration-content">
		                       					<h4 class="title">WP Version</h4>
		                       					<div class="desc">
		                       						<strong>4.8</strong>
		                       						<span>The version of WordPress installed on your site</span>
		                       					</div>
		                       				</div>
		                       			</div>
		                       			<div class="item" data-status="approved">
		                       				<div class="icon-status"></div>
		                       				<div class="rb-configuration-content">
		                       					<h4 class="title">WP Multisite</h4>
		                       					<div class="desc">
		                       						<strong>No</strong>
		                       						<span>Whether or not you have WordPress Multisite enabled</span>
		                       					</div>
		                       				</div>
		                       			</div>
	                       			</div>
	                       			<div class="item-group">
		                       			<div class="item" data-status="improvement">
		                       				<div class="icon-status"></div>
		                       				<div class="rb-configuration-content">
		                       					<h4 class="title">WP Debug Mode</h4>
		                       					<div class="desc">
		                       						<strong>4.8</strong>
		                       						<span>The version of WordPress installed on your site</span>
		                       					</div>
		                       				</div>
		                       			</div>
		                       			<div class="item" data-status="improvement">
		                       				<div class="icon-status"></div>
		                       				<div class="rb-configuration-content">
		                       					<h4 class="title">WP Memory Limit</h4>
		                       					<div class="desc">
		                       						<strong>40 MB</strong>
		                       						<span>Displays whether or not WordPress is in Debug Mode. This mode is used by developers to test the theme. We recommend you turn it off for an optimal user experience on your website. <a href="#" target="_blank">Learn how to do it</a></span>
		                       					</div>
		                       				</div>
		                       			</div>
	                       			</div>
	                       		</div>
	                       	</div>
							<div class="rb-configuration-check-st">
	                       		<h3 class="rb-title">Server Environment</h3>
	                       		<div class="rb-configuration-list">
	                       			<div class="item-group">
		                       			<div class="item" data-status="approved">
		                       				<div class="icon-status"></div>
		                       				<div class="rb-configuration-content">
		                       					<h4 class="title">Server Info</h4>
		                       					<div class="desc">
		                       						<strong>Apache/2.4.6 (CentOS) PHP/5.6.30</strong>
		                       						<span>Information about the web server that is currently hosting your site</span>
		                       					</div>
		                       				</div>
		                       			</div>
		                       			<div class="item" data-status="approved">
		                       				<div class="icon-status"></div>
		                       				<div class="rb-configuration-content">
		                       					<h4 class="title">PHP Version</h4>
		                       					<div class="desc">
		                       						<strong>5.6.30</strong>
		                       						<span>The version of PHP installed on your hosting server</span>
		                       					</div>
		                       				</div>
		                       			</div>
	                       			</div>
	                       			<div class="item-group">
		                       			<div class="item" data-status="approved">
		                       				<div class="icon-status"></div>
		                       				<div class="rb-configuration-content">
		                       					<h4 class="title">PHP Post Max Size</h4>
		                       					<div class="desc">
		                       						<strong>50 MB</strong>
		                       						<span>The largest file size that can be contained in one post</span>
		                       					</div>
		                       				</div>
		                       			</div>
		                       			<div class="item" data-status="approved">
		                       				<div class="icon-status"></div>
		                       				<div class="rb-configuration-content">
		                       					<h4 class="title">PHP Time Limit</h4>
		                       					<div class="desc">
		                       						<strong>1500</strong>
		                       						<span>The amount of time (in seconds) that your site will spend on a single operation before timing out (to avoid server lockups)</span>
		                       					</div>
		                       				</div>
		                       			</div>
	                       			</div>
	                       			<div class="item-group">
		                       			<div class="item" data-status="approved">
		                       				<div class="icon-status"></div>
		                       				<div class="rb-configuration-content">
		                       					<h4 class="title">PHP Max Input Vars</h4>
		                       					<div class="desc">
		                       						<strong>4000</strong>
		                       						<span>The maximum number of variables your server can use for a single function to avoid overloads.</span>
		                       					</div>
		                       				</div>
		                       			</div>
		                       			<div class="item" data-status="approved">
		                       				<div class="icon-status"></div>
		                       				<div class="rb-configuration-content">
		                       					<h4 class="title">SUHOSIN Installed</h4>
		                       					<div class="desc">
		                       						<strong>No</strong>
		                       						<span>Suhosin is an advanced protection system for PHP installations.</span>
		                       					</div>
		                       				</div>
		                       			</div>
	                       			</div>
	                       			<div class="item-group">
		                       			<div class="item" data-status="approved">
		                       				<div class="icon-status"></div>
		                       				<div class="rb-configuration-content">
		                       					<h4 class="title">ZipArchive</h4>
		                       					<div class="desc">
		                       						<strong>Yes</strong>
		                       						<span>ZipArchive is required for importing demos. They are used to import and export zip files specifically for sliders</span>
		                       					</div>
		                       				</div>
		                       			</div>
		                       			<div class="item" data-status="approved">
		                       				<div class="icon-status"></div>
		                       				<div class="rb-configuration-content">
		                       					<h4 class="title">MySQL Version</h4>
		                       					<div class="desc">
		                       						<strong>5.7.18</strong>
		                       						<span>The version of MySQL installed on your hosting server.</span>
		                       					</div>
		                       				</div>
		                       			</div>
	                       			</div>
	                       			<div class="item-group">
		                       			<div class="item" data-status="approved">
		                       				<div class="icon-status"></div>
		                       				<div class="rb-configuration-content">
		                       					<h4 class="title">Max Upload Size</h4>
		                       					<div class="desc">
		                       						<strong>50 MB</strong>
		                       						<span>The largest file size that can be uploaded to your WordPress installation</span>
		                       					</div>
		                       				</div>
		                       			</div>
		                       			<div class="item" data-status="approved">
		                       				<div class="icon-status"></div>
		                       				<div class="rb-configuration-content">
		                       					<h4 class="title">fsockopen/cURL</h4>
		                       					<div class="desc">
		                       						<strong>Yes</strong>
		                       						<span>Payment gateways can use cURL to communicate with remote servers to authorize payments, other plugins may also use it when communicating with remote services</span>
		                       					</div>
		                       				</div>
		                       			</div>
	                       			</div>
	                       		</div>
	                       </div>
	                    </div>
	                    <div class="la-form-group  text-right hidden-bar">
	                    	<button class="button button-secondary flat nextBtn" type="button">Skip this step</button>
	                    	<button class="button button-tertiary flat nextBtn" type="button">Continue</button>
	                    </div>
		            </div>
		            <div class="step-content" id="step-3">
	                    <div class="la-form-group">
	                        <h1 class="wz-title">Demo Select</h1>
	                        <div class="rb-tip">
	                        	<p><strong>Tips: </strong> Display some tips useful for user</p>
	                        </div>
	                    </div>
	                    <div class="rb-demo-list">
	                    	<div class="item">
	                    		<div class="inner">
	                    			<div class="pic">
	                    				<img src="<?php echo PLUGIN_URL ?>/assets/images/temp/post-1.jpg" alt="" class="rb-pic">
	                    			</div>
	                    			<div class="desc">
	                    				<h4 class="title">Example title</h4>
	                    				<span>Lorem ipsum dolor sit amet consectetur</span>
	                    			</div>
	                    			<div class="checked"></div>
	                    		</div>
	                    	</div>
	                    	<div class="item">
	                    		<div class="inner">
	                    			<div class="pic">
	                    				<img src="<?php echo PLUGIN_URL ?>/assets/images/temp/post-3.jpg" alt="" class="rb-pic">
	                    			</div>
	                    			<div class="desc">
	                    				<h4 class="title">Example title</h4>
	                    				<span>Lorem ipsum dolor sit amet consectetur</span>
	                    			</div>
	                    			<div class="checked"></div>
	                    		</div>
	                    	</div>
	                    	<div class="item">
	                    		<div class="inner">
	                    			<div class="pic">
	                    				<img src="<?php echo PLUGIN_URL ?>/assets/images/temp/post-4.jpg" alt="" class="rb-pic">
	                    			</div>
	                    			<div class="desc">
	                    				<h4 class="title">Example title</h4>
	                    				<span>Lorem ipsum dolor sit amet consectetur</span>
	                    			</div>
	                    			<div class="checked"></div>
	                    		</div>
	                    	</div>
	                    	<div class="item">
	                    		<div class="inner">
	                    			<div class="pic">
	                    				<img src="<?php echo PLUGIN_URL ?>/assets/images/temp/post-5.jpg" alt="" class="rb-pic">
	                    			</div>
	                    			<div class="desc">
	                    				<h4 class="title">Example title</h4>
	                    				<span>Lorem ipsum dolor sit amet consectetur</span>
	                    			</div>
	                    			<div class="checked"></div>
	                    		</div>
	                    	</div>
	                    	<div class="item">
	                    		<div class="inner">
	                    			<div class="pic">
	                    				<img src="<?php echo PLUGIN_URL ?>/assets/images/temp/post-8.jpg" alt="" class="rb-pic">
	                    			</div>
	                    			<div class="desc">
	                    				<h4 class="title">Example title</h4>
	                    				<span>Lorem ipsum dolor sit amet consectetur</span>
	                    			</div>
	                    			<div class="checked"></div>
	                    		</div>
	                    	</div>
	                    	<div class="item">
	                    		<div class="inner">
	                    			<div class="pic">
	                    				<img src="<?php echo PLUGIN_URL ?>/assets/images/temp/post-10.jpg" alt="" class="rb-pic">
	                    			</div>
	                    			<div class="desc">
	                    				<h4 class="title">Example title</h4>
	                    				<span>Lorem ipsum dolor sit amet consectetur</span>
	                    			</div>
	                    			<div class="checked"></div>
	                    		</div>
	                    	</div>
	                    	<div class="item">
	                    		<div class="inner">
	                    			<div class="pic">
	                    				<img src="<?php echo PLUGIN_URL ?>/assets/images/temp/post-28.jpg" alt="" class="rb-pic">
	                    			</div>
	                    			<div class="desc">
	                    				<h4 class="title">Example title</h4>
	                    				<span>Lorem ipsum dolor sit amet consectetur</span>
	                    			</div>
	                    			<div class="checked"></div>
	                    		</div>
	                    	</div>
	                    	<div class="item">
	                    		<div class="inner">
	                    			<div class="pic">
	                    				<img src="<?php echo PLUGIN_URL ?>/assets/images/temp/post-44.jpg" alt="" class="rb-pic">
	                    			</div>
	                    			<div class="desc">
	                    				<h4 class="title">Example title</h4>
	                    				<span>Lorem ipsum dolor sit amet consectetur</span>
	                    			</div>
	                    			<div class="checked"></div>
	                    		</div>
	                    	</div>
	                    </div>
	                    <div class="la-form-group  text-right hidden-bar">
	                    	<button class="button button-secondary flat nextBtn" type="button">Skip this step</button>
	                    	<button class="button button-tertiary flat nextBtn" type="button">Continue</button>
	                    </div>
		            </div>
		            <div class="step-content" id="step-4">
			    		<div class="la-form-group">
	                        <h1 class="wz-title">Your site will have these features</h1>
	                        <p class="description">
	                        	These are the plugins we include with this theme. Currently Solazu Unyson is the only required plugin that is needed to use it. You can activate, deactivate or update the plugins from this step.
	                        </p>
	                    </div>
	                    <div class="rb-features-check-st">
		                    <div class="rb-feature-list">
		                    	<div class="item active">
		                    		<div class="inner active">
		                    			<div class="ft-content">
		                    				<h4 class="title">Solazu Unyson <span class="required">Required</span></h4>
		                    				<div class="desc">All the built in extensions &amp; options work in perfect harmony. You’ll find developing on Solazu Unyson a breeze</div>
		                    			</div>
										<div class="rb-check-st"></div>
		                    		</div>
		                    	</div>
		                    	<div class="item active">
		                    		<div class="inner">
		                    			<div class="ft-content">
		                    				<h4 class="title">Revolution Slider <span class="required">Required</span></h4>
		                    				<div class="desc">Slider Revolution is an innovative, responsive WordPress Slider Plugin that displays your content the beautiful way.</div>
		                    			</div>
										<div class="rb-check-st"></div>
		                    		</div>
		                    	</div>
		                    	<div class="item active">
		                    		<div class="inner">
		                    			<div class="ft-content">
		                    				<h4 class="title">WPBakery Visual Composer <span class="required">Required</span></h4>
		                    				<div class="desc">Build a responsive website and manage your content easily with intuitive WordPress Front end editor. You can work with any WordPress theme of your choice.</div>
		                    			</div>
										<div class="rb-check-st"></div>
		                    		</div>
		                    	</div>
		                    </div>
	                    	<h3 class="rb-title">Recommend Plugins</h3>
	                    	<div class="rb-feature-list">
		                    	<div class="item">
		                    		<div class="inner active">
		                    			<div class="ft-content">
		                    				<h4 class="title">Newsletter <span class="recommend">recommend</span></h4>
		                    				<div class="desc">newsletter system: perfect for list building, you can easily create, send and track e-mails, headache-free</div>
		                    			</div>
										<div class="rb-check-st"></div>
		                    		</div>
		                    	</div>
		                    	<div class="item active">
		                    		<div class="inner">
		                    			<div class="ft-content">
		                    				<h4 class="title">Contact Form 7 <span class="recommend">recommend</span></h4>
		                    				<div class="desc">Contact Form 7 can manage multiple contact forms, plus you can customize the form and the mail contents flexibly with simple markup.</div>
		                    			</div>
										<div class="rb-check-st"></div>
		                    		</div>
		                    	</div>
		                    	<div class="item">
		                    		<div class="inner">
		                    			<div class="ft-content">
		                    				<h4 class="title">WooCommerce <span class="recommend">recommend</span></h4>
		                    				<div class="desc">WooCommerce is now the most popular eCommerce platform on the web, so you can rest assured you're in good company.</div>
		                    			</div>
										<div class="rb-check-st"></div>
		                    		</div>
		                    	</div>
		                    	<div class="item">
		                    		<div class="inner">
		                    			<div class="ft-content">
		                    				<h4 class="title">YITH WooCommerce Zoom Magnifier <span class="recommend">recommend</span></h4>
		                    				<div class="desc">YITH WooCommerce Zoom Magnifier you can add a zoom effect to all your product images.</div>
		                    			</div>
										<div class="rb-check-st"></div>
		                    		</div>
		                    	</div>
		                    </div>
	                    </div>
					    <div class="la-form-group  text-right hidden-bar">
	                    	<button class="button button-tertiary flat nextBtn" type="button">Continue</button>
                    	</div>
		            </div>
		            <div class="step-content" id="step-5">
		            	<div class="la-form-group">
			            	<h1 class="wz-title">Last check</h1>
			            	<p>It's taking a bit longer than expected, but we'll get there as fast as we can</p>
		            	</div>
		            	<h3 class="rb-title">Theme</h3>
						<div class="rb-demo-list">
	                    	<div class="item active">
	                    		<div class="inner">
	                    			<div class="pic">
	                    				<img src="<?php echo PLUGIN_URL ?>/assets/images/temp/post-1.jpg" alt="" class="rb-pic">
	                    			</div>
	                    			<div class="desc">
	                    				<h4 class="title">Example title</h4>
	                    				<span>Lorem ipsum dolor sit amet consectetur</span>
	                    			</div>
	                    			<div class="checked"></div>
	                    		</div>
	                    	</div>
	                    </div>
						<div class="rb-overview-list">
		            		<h3 class="rb-title">Functions / Plugins</h3>
							<table class="rb-table">
								<thead>
									<tr>
										<th class="detail">Detail</th>
										<th class="requirement">Requirement</th>
										<th class="status">Status</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="detail">
											<h4 class="title">Solazu Unyson</h4>
											<span class="desc">All the built in extensions &amp; options work in perfect harmony. You’ll find developing on Solazu Unyson a breeze</span>
										</td>
										<td class="requirement">
											<span class="required">required</span>
										</td>
										<td class="status">
											<span class="install-status"></span>
										</td>
									</tr>
									<tr>
										<td class="detail">
											<h4 class="title">WPBakery Visual Composer</h4>
											<span class="desc">Build a responsive website and manage your content easily with intuitive WordPress Front end editor</span>
										</td>
										<td class="requirement">
											<span class="required">required</span>
										</td>
										<td class="status">
											<span class="install-status"></span>
										</td>
									</tr>
									<tr>
										<td class="detail">
											<h4 class="title">Revolution Slider</h4>
											<span class="desc">Slider Revolution is an innovative, responsive WordPress Slider Plugin that displays your content the beautiful way.</span>
										</td>
										<td class="requirement">
											<span class="required">required</span>
										</td>
										<td class="status">
											<span class="install-status"></span>
										</td>
									</tr>
									<tr>
										<td class="detail">
											<h4 class="title">Newsletter</h4>
											<span class="desc">newsletter system: perfect for list building, you can easily create, send and track e-mails, headache-free.</span>
										</td>
										<td class="requirement">
											<span class="recommend">recommend</span>
										</td>
										<td class="status">
											<span class="install-status"></span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
	                    <div class="la-form-group  text-right hidden-bar">
	                    	<button class="button button-secondary flat nextBtn" type="button">Skip this step</button>
	                    	<button class="button button-tertiary flat nextBtn" type="button">Continue</button>
	                    </div>
		            </div>
		            <div class="step-content" id="step-6">
		            	<div class="la-form-group">
		            		<h1>Your Website is Ready!</h1>
		            		<p>Congratulation! the theme has been activated and your website is ready. Login to your Wordpress dashboard to make change and modify any of the default content to suit you need.</p>
		            		<p>please comback and <a href="#" target="_blank">Leave a 5 star rating</a> if you are happy with this theme.</p>
		            		<p>Follow <a href="#" target="_blank">@rubikThemes</a> on Twitter to see updates. Thanks!</p>
		            	</div>
							<div class="wc-setup-next-steps">
								<div class="wc-setup-next-steps-first">
									<h2>Next steps</h2>
									<ul>
										<li class="setup-product"><a class="button button-tertiary flat button-xlage" href="#">View your new Website!</a></li>
									</ul>
								</div>
								<div class="wc-setup-next-steps-last">
									<h2>Learn more</h2>
									<ul>
										<li class="video-walkthrough"><a href="https://docs.woocommerce.com/document/woocommerce-guided-tour-videos/?utm_source=setupwizard&amp;utm_medium=product&amp;utm_content=videos&amp;utm_campaign=woocommerceplugin">Watch the Guided Tour videos</a></li>
										<li class="newsletter"><a href="https://woocommerce.com/woocommerce-onboarding-email/?utm_source=setupwizard&amp;utm_medium=product&amp;utm_content=newsletter&amp;utm_campaign=woocommerceplugin">Get eCommerce advice in your inbox</a></li>
										<li class="learn-more"><a href="https://docs.woocommerce.com/documentation/plugins/woocommerce/getting-started/?utm_source=setupwizard&amp;utm_medium=product&amp;utm_content=docs&amp;utm_campaign=woocommerceplugin">Learn more about getting started</a></li>
									</ul>
								</div>
							</div>
	                    <div class="la-form-group  text-right hidden-bar">
	                    	<button type="submit" class="button button-tertiary flat nextBtn" type="button">Continue</button>
                    	</div>
		            </div>
		        </form>
		        <div class="sp sp-volume"></div>
		    </fieldset>
		</div>
	</div>
	<div id="ftp-modal" class="rb-modal">
		<div class="inner">
			<form required method="post">
				<div class="rb-modal-header">
					<h2 class="rb-heading">Connection Information</h2>
				</div>
				<div class="rb-modal-body">
					<p>To perform the requested action, WordPress needs to access your web server. Please enter your FTP credentials to proceed. If you do not remember your credentials, you should contact your web host.</p>
					<div class="form-ftp">
						<div class="la-form-group">
							<label class="lb-text">Hostname</label>
							<input type="text" required="required" class="la-form-control" name="" value="" placeholder="example: www.wordpress.com" />
							<span class="text-err">Please enter Hostname</span>
						</div>
						<div class="la-form-group">
							<label class="lb-text">FTP Username</label>
							<input type="text" required="required" class="la-form-control" name="" value=""/>
							<span class="text-err">Please enter FTP Username</span>
						</div>
						<div class="la-form-group">
							<label class="lb-text">FTP Password</label>
							<input type="password" required="required" class="la-form-control" name="" value="" />
							<span>This password will not be stored on the server.</span>
							<span class="text-err">Please enter FTP Password</span>
						</div>
						<div class="la-form-group">
							<label class="lb-text">FTP Password</label>
							<div class="rb-radio">
								<input type="radio" name="ftp" value="ftp" />
								<span class="lb-text">FTP</span>
							</div>
							<div class="rb-radio">
								<input type="radio" name="ftp" value="ftp" />
								<span class="lb-text">FTPS (SSL)</span>
							</div>
						</div>
					</div>
				</div>
				<div class="rb-modal-footer">
					<div class="text-right">
						<button type="button" class="button-tertiary flat button button-connect">Proceed</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<script type="text/javascript" src="<?php echo PLUGIN_URL ?>/assets/libs/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="<?php echo PLUGIN_URL ?>/assets/libs/jquery-ui-1.11.4/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo PLUGIN_URL ?>/assets/libs/select2/js/select2.min.js"></script>
	<script type="text/javascript" src="<?php echo PLUGIN_URL ?>/assets/js/learnmaster-wizard.js"></script>
</body>
</html>