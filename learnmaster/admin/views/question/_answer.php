<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
$minAnswer = 3;
 ?>
<?php for($i = 0; $i < $minAnswer; $i++):?>
<div class="answer-item">
    <div class="la-radio-group">
        <input type="radio" class="radiogroup" name="Question[answer][<?php echo $i?>][correct]" <?php if($i == 0 ) echo 'checked';?> value="1" required>
        <span class="ip-text"></span>
    </div>
    <div class="la-form-group">
        <textarea name="Question[answer][<?php echo $i?>][content]" class="la-form-control"></textarea>
    </div>
    <div class="la-btn-group">
        <button type="button" class="button button-link button-remove btn-remove-answer"><span class="dashicons dashicons-trash"></span>
        </button>
    </div>
    <div class="clear"></div>
    <div class="la-textbox-group">
        <input type="text" class="form-control la-input" placeholder="Explain why this is or isn't the best answer." name="Question[answer][<?php echo $i?>][hint]" value="" maxlength="600" />
        <span class="la-input-length"></span>
    </div>
</div>
<?php endfor;?>
