<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
 ?>

<fieldset class="lema-setting-block">
    <legend><?php echo __('Learn Master extensions', 'lema')?></legend>
   <!-- <div class="lema-tool-bar">
        <div class=" lema-right">
            <form method="get" class="lema-extension-search">
                <div class="la-form-group">
                    <label><?php /*echo __('Find : ', 'lema')*/?></label>
                    <?php /*foreach ($_GET as $key => $value):*/?>
                    <input type="hidden" name="<?php /*echo esc_html($key)*/?>" value="<?php /*echo esc_html($value)*/?>" />
                    <?php /*endforeach;*/?>
                    <input type="text" class="lema-search-input" name="q" value="<?php /*echo isset($_GET['q']) ? esc_html($_GET['q']) : ''*/?>" />
                    <button type="submit"><i class="fa fa-search"></i> </button>
                </div>
            </form>
        </div>

    </div>-->

    <div class="extension-block">
        <?php if(empty($extensions)) :?>
            <div class="notice notice-error">
                <?php echo __('No extension found!', 'lema')?>
            </div>
        <?php else:?>
            <?php foreach ($extensions as $extension) :?>
                <div class="extension-item">
                    <div class="extension-image">
                        <a href="<?php echo $extension->url?>" target="_blank"> <img src="<?php echo $extension->image?>" /></a>
                    </div>
                    <h3><a href="<?php echo $extension->url?>" target="_blank"><?php echo $extension->name?></a> </h3>
                    <div class="extension-desc">
                        <?php echo $extension->description?>
                    </div>
                    <div class="extension-bottom lema-tool-bar">
                        <div class="lema-left extension-price">
                            <a href="<?php echo $extension->url?>" target="_blank"><?php echo lema()->helpers->general->currencyFormat($extension->price)?></a>
                        </div>
                        <div class="lema-right extension-type">
                            <small><?php echo $extension->type?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
        <?php endif;?>
    </div>
</fieldset>
