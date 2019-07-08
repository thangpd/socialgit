<div id="la-modal-customfield" class="la-popup lema-popup">
    <div class="la-popup-wrap" style="background: #fff">
        <div class="la-popup-header ui-draggable-handle">
            <div class="button-collapse" title="Collapse popup">
                <span class="flaticon-arrows-7"></span>
            </div>
            <h3 class="title"><?php echo __('Course custom attribute', 'lema')?></h3>
            <span class="la-button button-save" title="Save changes (ctrl + s)"><span
                    class="flaticon-checked"></span></span>
            <span class="la-button button-cancel" title="Cancel & close popup"><span
                    class="fa fa-close"></span></span>
        </div>
        <div id="la-modal-customfield-content" class="tab-content" style="background: #fff;display: inline-block;width: 100%;">
            <div class="col-50" style="padding: 0">
                <div class="padding-content" style="padding : 10px">
                    <?php echo __('Select current attributes', 'lema')?>
                    <select id="add-custom-fields" class="form-control la_form_control">
                        <?php foreach ($fields as $field):?>
                            <option value="<?php echo $field['name']?>"><?php echo $field['label']?></option>
                        <?php endforeach;?>
                    </select>

                    <br />
                    <br />
                    <a href="#" class="button button-primary"><?php echo __('Add', 'lema')?></a>
                </div>
            </div>
            <div class="col-50" style="padding: 0">
                <?php lema()->helpers->general->registerPjax('la-modal-content')?>
                <form class="pjaxform" data-container="#la-modal-content" data-target="data-list"  action="<?php echo admin_url('admin-ajax.php')?>"  method="post">
                    <strong><?php echo __('Create new custom attribute', 'lema')?></strong>
                    <div class="form-field form-required">
                        <label><?php echo __('Label', 'lema')?></label>
                        <input type="text" name="Field[label]" id="custom-field-label" value="<?php echo $field['label']?>" />
                    </div>
                    <input readonly type="hidden" name="Field[name]" id="custom-field-name" value="<?php echo $field['name']?>"/>
                    <div class="form-field form-required">
                        <label><?php echo __('Type', 'lema')?></label><br />
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
                        <textarea rows="5" name="Field[default]"></textarea>
                        <small class="hint hide" id="customfield-options">
                            <?php echo __('Put each option on separated line', 'lema')?>
                        </small>
                    </div>

                    <div class="form-field form-required">
                        <button type="submit" class="button button-primary"><?php echo __('Create and add', 'lema')?></button>
                    </div>
                </form>

                <?php lema()->helpers->general->endPjax()?>
            </div>

        </div>
        <div class="la-popup-footer">
            <div class="la-controls">
                <button type="button" class="button button-secondary flat button-cancel"><span
                        class="fa fa-close"></span> <?php echo __('Close' ,'lema')?>
                </button>
            </div>
        </div>
    </div>

</div>

