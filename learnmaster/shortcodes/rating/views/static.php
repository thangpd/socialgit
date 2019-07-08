<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
if (isset($data['template']) && !empty($data['template']))  {
    $template = $data['template'];
    $rating_replace = '';
    $total_replace = '';
    $static_replace = '';
    $label_replace = '';

    if($data['label'] !== ''){
        $label_replace = '<label class="lema-shortcode lema-rating-label">'.$data['label'].'</label>';
    }
    if(!empty($status->avg)){
        $rating_replace = '<div class="lema-star-rating view-only">
          <span class="bg-rate"></span>
          <span class="rating" style="width:'.($status->avg * 20).'%"></span>
        </div>';
    }
    if((float)$data['static_value'] > 0){
        $static_replace = '<label class="lema-shortcode lema-rating-value">
        <strong>'.number_format($data['static_value'], 1).'</strong>
    </label>';
    }

    if(!empty($data['static_total'])){
        $total_replace = 
        '<div class="lema-number-rating">
            <label class="lema-shortcode lema-rating-total">
                ('.number_format($data['static_total'], 0).')
            </label>
        </div>';
    }
    $template = str_replace('{label}', $label_replace, $template);
    $template = str_replace('{rating}', $rating_replace, $template);
    $template = str_replace('{number}', $static_replace, $template);
    $template = str_replace('{total}', $total_replace, $template);

}
 ?>

<div class="lema-rating lema-rating-custom <?php $context->defineShortcodeBlock()?>">
    <?php echo $template?>
</div>

