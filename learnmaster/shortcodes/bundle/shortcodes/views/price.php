<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
?>

<div class="bundle-price-block lema-discount">
    <?php if(isset($price['sale'])):?>
        <?php if($data['show_discount_value']): ?>
            <div class="price">
                <del><?php echo $price['regular']?></del>
			    <?php if ( $data['show_discount_percent'] && $discount_percen > 0):?>
                    <span>(<?php echo $discount_percen?>% Off)</span>
			    <?php endif;?>
            </div>
        <?php endif; ?>
        <div class="time-sale">
            <?php echo $price['sale']?>
            <?php if($data['show_remain_time'] && isset($timeLeft)):?>
                <span><?php echo $timeLeft?></span>
            <?php endif;?>
        </div>
    <?php else :?>
        <div class="time-sale">
            <?php echo $price['regular']?>
        </div>
    <?php endif;?>


</div>
