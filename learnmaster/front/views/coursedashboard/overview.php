<?php extract($dataOverview); ?>
<div class="tab-panel active" data-content="overview">

	<h3 class="lema-tab-title"><?php echo esc_html__('About This Course', 'lema') ?></h3>
	<div class="lema-course-info-st">
		<div class="top-wrapper">
			<div class="lema-columns lema-column-2">
				<div class="item">
					<h4 class="lema-title"><?php echo esc_html__('Number', 'lema') ?></h4>
					<div class="info-list">
						<div class="info-item">
							<label for="">Lectures:</label>
							<span>47</span>
						</div>
						<!-- level -->
						<?php if ( isset($listLevel[$courseModel->course_level]) && !empty($listLevel[$courseModel->course_level]) ): ?>
							<div class="info-item">
								<label for=""><?php echo esc_html__('Skills:', 'lema') ?></label>
								<span><?php echo esc_html($listLevel[$courseModel->course_level]) ?></span>
							</div>
						<?php endif; ?>

						<!-- language -->
						<?php if ( isset($listLanguage[$courseModel->course_language]) && !empty($listLanguage[$courseModel->course_language]) ): ?>
							<div class="info-item">
								<label for=""><?php echo esc_html__('Languages:', 'lema') ?></label>
								<span><?php echo esc_html($listLanguage[$courseModel->course_language]) ?></span>
							</div>
						<?php endif; ?>

						<?php if ( isset($courseModel->course_review) && !empty($courseModel->course_review)): ?>
							<div class="info-item">
								<label for=""><?php echo esc_html__('Reviews:', 'lema') ?></label>
								<span><?php echo $courseModel->course_review ?></span>
							</div>
						<?php endif; ?>

						<?php if ( isset($courseModel->course_number_video) && !empty($courseModel->course_number_video)): ?>
							<div class="info-item">
								<label for=""><?php echo esc_html__('Videos:', 'lema') ?></label>
								<span><?php echo $courseModel->course_number_video ?></span>
							</div>
						<?php endif; ?>


						<?php if ( isset($courseModel->course_student) && !empty($courseModel->course_student)): ?>
							<div class="info-item">
								<label for=""><?php echo esc_html__('Students:', 'lema') ?></label>
								<span><?php echo $courseModel->course_student ?></span>
							</div>
						<?php endif; ?>

					</div>
				</div>
			</div>
		</div>

		<!-- description -->
		<?php if ( isset($courseDescription) && !empty($courseDescription)): ?>
			<h4 class="lema-title">Description</h4>
			<div class="lema-view-more">
				<div class="lema-view-more-content">
					<?php echo esc_html($courseModel->course_description);?>
				</div>
				<button class="show-more-content">
					<i class="icon ion-ios-arrow-thin-down"></i>
				</button>
			</div>
		<?php endif; ?>

		<?php if (isset($courseInstructor) && !empty($courseInstructor)): ?>
			<h3 class="lema-tab-title">Instructor</h3>
			<?php echo lema_do_shortcode($courseInstructor) ?>
		<?php endif; ?>
	</div>
</div>