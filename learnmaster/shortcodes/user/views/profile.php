<!-- User Profile -->
<div class="<?php $context->defineShortcodeBlock()?> padding-100">
	<div class="lema-user-profile">
		<div class="block-wrapper">
			<div class="img-wrapper">
			<?php echo get_avatar($user->ID)?>
			</div>
			<div class="content-wrapper">
				<div class="name">
					<?php echo $user->display_name; ?>
				</div>
				<div class="position">
					<?php echo $user->job_title; ?>
				</div>
				<div class="social">
					<ul class="list-wrapper">
						<?php foreach(\lema\models\UserModel::listSocial() as $key=>$social){ ?>
						<li>
							<a href="<?php the_author_meta( $key, $user->ID ); ?>" class="link">
								<i class="icon fa <?php echo $social['icon']?>"></i>
							</a>
						</li>
						<?php } ?>
					</ul>
				</div>
				<div class="content-text">
					<?php the_author_meta('description',$user->ID); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End User Profile -->
<!-- Tab -->