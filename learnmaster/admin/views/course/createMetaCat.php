<?php
/**
 * @var \lema\core\components\Form $form
 * @var \lema\models\CourseModel $model
 * @var \lema\admin\controllers\CourseController $context
 */
$fields = lema()->helpers->form->generateFormElement($model, $form);
foreach ($fields as $attr => $field)
{
	echo $field;
}
wp_enqueue_media();
