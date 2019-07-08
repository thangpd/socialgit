<?php
/**
 * Created by PhpStorm.
 * User: Solazu
 * Date: 7/28/2017
 * Time: 11:40 AM
 * @var \lema\helpers\form\CustomElement $control
 */
$name_field = $value_field = $label_field = $src_field = $class_field = '';
if ( isset($control->name) ) {
    $name_field = $control->name;
}

if ( isset($control->value) ) {
    $value_field = $control->value;
}

if ( isset($control->label) ) {
    $label_field = $control->label;
}

if ( !empty($value_field) ) {
    $src_field = wp_get_attachment_url($value_field);
}
if ( isset($control->class) ) {
    $class_field = $control->class;
}
?>
<table class="la-form-group fm-attach-image form-table add-image"  >
    <tbody>
        <tr>
            <th><?php echo esc_html($label_field) ?></th>
             <td>
            <div class="custom_image_container"></div>
            <div class="button-secondary button flat upload_custom_image">Add Image</div>
            <div class="button-secondary button flat delete_custom_image hidden">Delete Image</div>
            <input type="hidden" class="<?php echo esc_attr($class_field) ?>" value="<?php echo esc_attr($value_field) ?>" name="<?php echo esc_attr($name_field) ?>" data-src="<?php echo esc_url($src_field) ?>">
        </td> 
        </tr>  
    </tbody>
   
</table>