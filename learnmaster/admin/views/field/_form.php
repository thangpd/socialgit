<?php
/**
 * @project  eduall
 * @copyright © 2017 by Chuke Hill Co.,LTD
 * @author ivoglent
 * @time  11/13/17.
 */
?>
<h2><?php echo __('Add/Edit', 'lema')?></h2>
<form method="post">
    <div class="form-field form-required">
        <label><?php echo __('Label', 'lema')?></label>
        <input type="text" name="Field[label]" id="custom-field-label" value="<?php echo $field['label']?>" />
    </div>
    <input readonly type="hidden" name="Field[name]" id="custom-field-name" value="<?php echo $field['name']?>"/>
    <div class="form-field form-required">
        <label><?php echo __('Type', 'lema')?></label>
        <select name="Field[type]" id="customfield-types">
            <?php foreach (\lema\models\FieldModel::getFieldTypes() as $name => $label) :?>
                <option value="<?php echo $name?>" <?php echo $name == $field['type'] ? ' selected ' : ''?> id="<?php echo $name?>">
                    <?php echo $label?>
                </option>
            <?php endforeach;?>
        </select>
    </div>
    <div class="form-field form-required">
        <label><?php echo __('Default value', 'lema')?></label>
        <textarea rows="5" name="Field[default]"><?php echo $field['default']?></textarea>
        <small class="hint hide" id="customfield-options">
            <?php echo __('Put each option on separated line', 'lema')?>
        </small>
    </div>
    <div class="form-field form-required">
        <label>
        <input type="checkbox" rows="5" name="Field[primary]" value="1" <?php echo (isset($field['primary']) && $field['primary']) ? ' checked ' : ''?> />
            <?php echo __('This is primary field', 'lema')?>
        </label>
    </div>
    <div class="form-field form-required">
        <label>
            <input type="checkbox" rows="5" name="Field[filterable]" value="1" <?php echo (isset($field['filterable']) && $field['filterable']) ? ' checked ' : ''?> />
            <?php echo __('Is this filterable?', 'lema')?>
        </label>
    </div>
    <div class="form-field form-required">
        <button type="submit" class="button button-primary"><?php echo __('Submit', 'lema')?></button>
        <?php if (!empty($field['name'])):?>
            <button name="deleteField" onclick="return confirm('<?php echo __('Do you really want to delete this field', 'lema')?>');" type="submit" class="button button-danger"><?php echo __('Delete', 'lema')?></button>
            <p class="pull-right" style="margin-right: 20px"><a href="edit.php?post_type=course&page=course-custom-fields">Add new</a></p>
        <?php endif;?>
    </div>
</form>