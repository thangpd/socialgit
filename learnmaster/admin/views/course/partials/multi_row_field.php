<?php
/**
 * Created by PhpStorm.
 * User: Solazu
 * Date: 7/28/2017
 * Time: 11:40 AM
 * @var \lema\helpers\form\CustomElement $control
 */
$name_field = isset($control->name)?$control->name:'';
$value_field = isset($control->value)?$control->value:[];
//$value_field = json_decode($value_field, true);
if (!is_array($value_field)) {
    $value_field = array();
}
$label_field = isset($control->label)?$control->label:'';
$src_field = !empty($value_field)?wp_get_attachment_url($value_field):'';
$placeholder = '';
if (isset($control->placeholder)) {
    $placeholder = $control->placeholder;
}

$sc_class = "";
if ( !empty($control->class) ) {
    $sc_class = $control->class;
}
?>
<div class="la-form-group block-add-more-option <?php echo esc_attr($sc_class) ?>">
    <label for=""><?php echo $label_field  ?></label>
    <div class="la-block-content">
        <div class="input-group">
            <?php for ($i=0; $i< count($value_field) && !empty($value_field[$i]); $i++): ?>
            <div class="field">
                <input type="text" name="<?php echo $name_field ?>" value="<?php echo $value_field[$i] ?>" placeholder="<?php echo $placeholder ?>" >
                <a href="javascript:void(0)" class="btn-del"></a>
            </div>
            <?php endfor; ?>
            <div class="field">
                <input type="text" name="<?php echo $name_field ?>" value="" placeholder="<?php echo $placeholder ?>" class="la_form_control" >
                <a href="javascript:void(0)" class="btn-del"></a>
            </div>
        </div>
        <button type="button" class="button-secondary button flat modal-button button-add-more">
            <span class="fa fa-plus"></span> Add an item
        </button>
    </div>
</div>