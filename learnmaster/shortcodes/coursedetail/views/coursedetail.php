<?php

$style = $data['style'];

?>


<div class="sc_course_detail <?php echo esc_attr( $style ); ?>">
	<?php


	echo $context->render_course_content( $data['courseid'] );

	?>

</div>







