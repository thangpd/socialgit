<div class="cart-list">
<?php if($list_items){ foreach($list_items as $item): ?>
  <div class="item">
    <a class="pic" href="<?php echo get_permalink($item->ID)?>">
    <?php echo get_avatar($item->ID)?>
    </a>
    <a href="<?php echo get_permalink($item->ID)?>" class="title"><?php echo $item->post_title?></a>
    <div class="lema-discount">
       <div class="time-sale"><?php echo $item->course_price?> <!-- <del>$sale</del> --></div>
    </div>
  </div>
<?php endforeach; }?>
</div>