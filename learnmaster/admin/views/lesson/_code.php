<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */

$files = @$control->params['value'];

if (!is_array($files)) {
    $files = [];
}
$i = 0;
?>

<div class="la-form-group">
    <button type="button"  class="button-secondary button flat modal-button add-media-button"  data-callback="updateCodeList" data-multiple="true" data-modal="modal-id-03"><span class="fa fa-plus"></span> Add from library</button>
</div>
<div>
    <strong>Tips: </strong>
    <span>Only available for Python and Ruby for now. You can upload .py and .rb files.</span>
</div>


<div id="code-files">
    <?php foreach ($files as $file):?>

        <?php $i++; $file = json_decode($file, true)?>
        <div class="col-100 file-item">
            <input type="hidden" name="Lesson[resource_code][]" value="<?php echo htmlspecialchars(json_encode($file))?>">
            <div class="col col-10">#<?php echo $i?></div>
            <div class="col col-80"><?php echo $file['filename']?></div>
            <div class="col col-10">
                <span  data-nonce="<?php echo wp_create_nonce('lema_nonce') ?>" class="btn-remove remove-code-file" title="Remove"></span>
            </div>
        </div>
    <?php endforeach;?>
</div>
