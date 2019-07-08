<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
?>
<fieldset class="lema-shortcode lema-rating-content <?php echo ( ($data['readonly'] == "false") && is_user_logged_in()) ? 'enabled' : 'readonly'?>">
    <?php for($i=1; $i <= 5; $i++):?>
    <input type="radio" id="star<?php echo $i?>" name="rate" value="<?php echo 6-$i?>" />
        <label class="full" for="star<?php echo $i?>"></label>
    <?php endfor;?>
</fieldset>
