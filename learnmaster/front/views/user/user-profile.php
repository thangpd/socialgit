<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
 ?>
 <style>
 	.padding-100 {
 		padding-top: 100px;
 		padding-bottom: 100px;
 	}
 </style>
 <div class="lema-user-profile-page">
	<div class="lema-page-content">
		<div class="container">
			<h2 class="lema-user-title">User Profile</h2>
			<?php
				echo $context->render('profile', ['user' => $user]);
				if(in_array('lema_student', $user->roles)){
			?>

			<div class="lema-tabs lema-user-profile-tabs">
				<div class="tab-list-wrapper">
					<ul class="tab-list">
						<?php foreach($context->showList() as $key=>$list){ ?>
						<li>
							<a data-tab="tab_<?php echo $list['name']?>" class="tab-link <?php if($key == 0) echo 'active'; ?>">
								<?php echo $list['title']?>
							</a>
						</li>
						<?php } ?>
					</ul>
				</div>
				<div class="tab-content-wrapper">
					<?php foreach($context->showList() as $key=>$list){ ?>
					<div class="tab-panel <?php if($key==0) echo 'active'; ?>" data-content="tab_<?php echo $list['name']?>">
						<div class="lema-course-list ">
							<?php echo lema_do_shortcode('[lema_course_list_'.$list['name'].' cols_on_row=3 course_vc="1" template="template_1" template_1="style_1" ]'); ?>
	   					</div>
					</div>
					<?php } ?>
				</div>
			</div>
			<!-- End tab -->
			<?php } else {?>
	            <div class="lema-message error">
	                <?php echo __('This page allowed student access only!', 'lema')?>
	            </div>
	        <?php }?>
		</div>			
	</div>
</div>