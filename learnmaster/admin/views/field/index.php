<?php
/**
 * @project  eduall
 * @copyright © 2017 by Chuke Hill Co.,LTD
 * @author ivoglent
 * @time  11/13/17.
 */
?>
<h1 class="wp-heading-inline"><?php echo __('Course custom fields', 'lema')?></h1>
<div id="col-container" class="wp-clearfix">
    <div id="col-left">
        <div class="col-wrap">
            <div class="form-wrap">
                <?php $context->render('_form', ['field' => $field])?>
            </div>
        </div>
    </div>
    <div id="col-right">
        <?php $context->render('_list', ['fields' => $fields])?>
    </div>
</div>
