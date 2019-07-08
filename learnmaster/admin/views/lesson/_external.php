<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 *
 * @var \lema\helpers\form\CustomElement $control
 */

$value = @$control->params['value'];
if ($value && !is_array($value)) {
    $value = json_decode($value, true);
}
?>
<div class="la-form-group">
    <label for="" class="lb-text">External source</label>
    <div class="la-sub-desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>
</div>
<div class="la-form-group">
    <label for="" class="lb-text">Title *</label>
    <div class="la-textbox-group">
        <input type="text" class="form-control la-input" name="Lesson[resource_external][title]" value="<?php echo $value ? $value['title'] : ''?>" placeholder="A description title" maxlength="80">
        <span class="la-input-length"></span>
    </div>
</div>
<div class="la-form-group">
    <label for="" class="lb-text">URL</label>
    <input type="text" name="Lesson[resource_external][url]" class="la-form-control" value="<?php echo $value ? $value['url'] : ''?>" placeholder="http://example.com" />
</div>
<div>
    <strong>Tips: </strong>
    <span>A resource is for any type of document that can be used to help students in the lecture. This file is going to be seen as a lecture extra. Make sure everything is legible and the file size is less than 1 GiB.</span>
</div>
