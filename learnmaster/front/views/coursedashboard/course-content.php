<?php 
extract($dataCourseContent);

$dataCurriculum = $courseModel->getCurriculum();
$dataProcessItem = get_user_meta(get_current_user_id(), "learning_progress_{$courseId}", true);
	if(!is_array($dataProcessItem)){
		$dataProcessItem = [];
	}

?>
<div class="tab-panel" data-content="courseContent_tab">
	<div class="curriculum-list-wrapper">
		<div class="top-bar">
			<div class="col-left">
				<div class="lema-form-group">
					<input id="lema-search-lessons" type="text" class="lema-form-control" placeholder="<?php echo esc_attr__('Search lesson', 'lema') ?>" />
					<button type="button" class="button button-search"><?php echo esc_html__('Search', 'lema') ?></button>
				</div>
			</div>
			<div class="col-right">
				
			</div>
		</div>
	</div>
	<div class="lema-curriculum-list">
		<?php 
		$index=0;
		foreach ($dataCurriculum as $chapterKey => $chapter): 

			$index++;
		$chapterModel = \lema\models\ChapterModel::findOne($chapterKey);
		if ( isset($chapterModel) ):
		$lessonIds = $chapterModel->getLessons();
		$successLesson = 0;

		for ($i=0; $i < count($lessonIds); $i++) {
			if (isset($dataProcessItem[$lessonIds[$i]->ID]) && $dataProcessItem[$lessonIds[$i]->ID]) {
				$successLesson++;
			}
		}

		?>
		<!-- item -->
		<div class="lema-curriculum-chapter lema-collapsed">
			<div class="lema-chapter-heading">
				<div class="top">
					<span class="lema-index"><?php echo esc_html__('Section ', 'lema').$index.':' ?></span>
					<?php if ( isset($chapterModel->post->post_title) && !empty($chapterModel->post->post_title) ) : ?>
					<div class="lm-main">
						<h3 class="lema-chapter-title"><?php echo $chapterModel->post->post_title ?></h3>
					</div>
				<?php endif; ?>
					<div class="lema-total-lesson-num">
						<span class="curr"><?php echo esc_html($successLesson) ?></span>
						<span class="line">/</span>
						<span class="total"><?php echo esc_html(count($lessonIds)) ?></span>
					</div>
				</div>

			</div>
			<div class="lema-curriculum-body">
				<!-- LESSON -->
				<?php
                /** @var \lema\models\CourseModel $courseModel */

                for ($i=0; $i < count($lessonIds); $i++) :
				$item 						= $lessonIds[$i];
                $itemResources 				= lema()->wp->get_post_meta($item->ID, 'resource_external', true);
                $itemType 				= lema()->wp->get_post_meta($item->ID, 'content_type', true);
				$itemCodeResources			= lema()->wp->get_post_meta($item->ID, 'resource_code', true);
				$itemDownloadableResources 	= lema()->wp->get_post_meta($item->ID, 'resource_downloadable', true);
				$queyLesson 					= '?lesson='.$item->ID;
				$linkLesson					= $courseModel->getLearningUrl($queyLesson);
				$is_active 					= '';
				if (isset($dataProcessItem[$item->ID]) && $dataProcessItem[$item->ID]) {
					$is_active = 'active';
				}
				?>
				<div class="lema-curriculum-lesson <?php echo esc_attr($itemType) ?>">
					<div class="lema-icon-check-readonly <?php echo esc_attr($is_active); ?>" disabled></div>
					<div class="lema-lesson-heading">
						<span class="lema-icon-type"></span>
						<span class="lema-index"><?php echo esc_html__('Lecture ', 'lema').$index.':' ?></span>
						<h4 class="lema-lesson-title"><a href="<?php echo esc_url($linkLesson) ?>"><?php echo esc_html($item->post_title) ?></a></h4>
					</div>
					<div class="sub-desc"><?php echo apply_filters('the_content', $item->post_content) ?></div>
					<?php
                    $total = (isset($itemResources['title']) && !empty($itemResources['title'])) || !empty($itemCodeResources) || !empty($itemDownloadableResources);
                    if ( $total) :
                    ?>
                    <div class="lema-resource-list">
						<?php
                        // resource
                        if ( isset($itemResources['title']) && $itemResources['title'] )  {
                            ?>
                            <div class="resource-item">
                                <a href="<?php echo esc_attr($itemResources['url']) ?>" class="lema-link"><?php echo esc_html($itemResources['title']) ?></a>
                            </div>
                            <?php
                        }
							// code resource
						for ($j=0; $j < count($itemCodeResources); $j++) {
							if ( isset($itemCodeResources[$i]) && !empty($itemCodeResources[$i]) ) {
								if (is_string($itemCodeResources[$i])) {
									$itemCodeResources[$i] = json_decode($itemCodeResources[$i], true);
								}
								if ( !empty($itemCodeResources[$i]) ) {
								    $file_name = $file_url = '';
								    if ( isset($itemCodeResources[$i]['url']) ) {
								        $file_url = $itemCodeResources[$i]['url'];
                                    }

									if ( isset($itemCodeResources[$i]['filename']) ) {
										$file_name = $itemCodeResources[$i]['filename'];
									}
									?>
                                    <div class="resource-item">
                                        <a href="<?php echo esc_attr($file_url) ?>" class="lema-link"><?php echo esc_html($file_name) ?></a>
                                    </div>
									<?php
                                }
							}
						}
							// code download resource
						for ($j=0; $j < count($itemDownloadableResources); $j++) {
							if ( isset($itemDownloadableResources[$i]) && !empty($itemDownloadableResources[$i]) ) {
								if (is_string($itemDownloadableResources[$i])) {
									$itemDownloadableResources[$i] = json_decode($itemDownloadableResources[$i], true);
								}

								if ( !empty($itemDownloadableResources[$i]) ) {
								    $file_name = $file_url = '';
								    if (isset($itemDownloadableResources[$i]['filename'])) {
								        $file_name = $itemDownloadableResources[$i]['filename'];
                                    }

									if (isset($itemDownloadableResources[$i]['url'])) {
										$file_url = $itemDownloadableResources[$i]['url'];
									}

									?>
                                    <div class="resource-item">
                                        <a href="<?php echo esc_attr($file_url) ?>" class="lema-link"><?php echo esc_html($file_name) ?></a>
                                    </div>
									<?php
								}
							}
						}
						?>
					</div>
                    <?php endif; ?>
				</div>
				<?php
				endfor;
				?>
			</div>
		</div>
		<!-- end item -->
		<?php 
		endif;
		endforeach; 	
		?>
	</div>
</div>