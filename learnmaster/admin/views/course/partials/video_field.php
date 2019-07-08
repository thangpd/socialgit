<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */

if (isset($control->name)) {
    $name_field = $control->name;
}

if (isset($control->value)) {
    $value_field = $control->value;
}

if (isset($control->label)) {
    $label_field = $control->label;
}

if (!empty($value_field)) {
    $src_field = wp_get_attachment_url($value_field);
}

$sc_class = "";
if ( !empty($control->class) ) {
    $sc_class = $control->class;
}
?>
<div class="la-form-group fm-attach-video <?php echo esc_attr($sc_class) ?>">
    <label for=""><?php echo $label_field ?></label>
    <div class="la-block-content">
    	<div class="custom_video_container"></div>
	    <button class="button-secondary button flat upload_custom_video">Add Video</button>
	    <button class="button-secondary button flat delete_custom_video hidden">Delete Video</button>
	    <input type="hidden" value="<?php echo !empty($value_field) ? $value_field : '' ?>" name="<?php echo !empty($name_field) ? $name_field : '' ?>" data-src="<?php echo !empty($src_field) ? $src_field : '' ?>">
    </div>
</div>
