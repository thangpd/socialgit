<?php 
//message
$messSuccessItem = esc_html__("items completed", 'lema');
$messResetItems = esc_html__("Reset progress", 'lema');
//get course model
$courseId = $data['post_id'];
$courseModel = \lema\models\CourseModel::findOne($courseId);

$numTotal = $persent = $numSuccess = 0;

//get status lesson
$dataSuccess = get_user_meta(get_current_user_id(), "learning_progress_{$courseId}", true);
if (!is_array($dataSuccess)) {
    $dataSuccess = [];
}
foreach($dataSuccess as $item){
	if($item == '1'){
		$numSuccess++;
	}
}
//
$dataCurriculum = $courseModel->getCurriculum();
if($dataCurriculum){
	foreach ($dataCurriculum as $chapterKey => $obj) {
		$numTotal += (count($obj, COUNT_RECURSIVE) - count($obj));
	}

	if ($numTotal > 0 && $numSuccess > 0) {
		$persent = intval($numSuccess/$numTotal*100);
	}

	?>
	<div class="lema-lesson-progress clear">
		<div class="lema-progress-bar" data-percent="<?php echo esc_attr($persent) ?>">
			<div class="lema-progress-percent" style="width: <?php echo esc_attr($persent).'%'; ?>"></div>
		</div>
		<div class="progress-description">
			<span class="lesson-sucess"><?php echo esc_html( $numSuccess) ?></span>
			<span> <?php echo esc_html__('of', 'lema') ?> </span>
			<span class="lesson-total"> <?php echo esc_html( $numTotal) ?> </span>
			<span><?php echo $messSuccessItem; ?></span>
		</div>
		<a href="#" class="lema-link reset-lesson" style="display: none;"><?php echo $messResetItems; ?></a>
	</div>
<?php } ?>