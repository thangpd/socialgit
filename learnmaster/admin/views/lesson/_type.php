<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 * @var \lema\helpers\form\CustomElement $control
 */

$value =  @$control->params['value'];
$dataTypes = [
    'article' =>  LEMA_PATH_PLUGIN . '/assets/admin/images/file.png',
    'video' =>  LEMA_PATH_PLUGIN . '/assets/admin/images/mp4.png',
    'audio' =>  LEMA_PATH_PLUGIN . '/assets/admin/images/mp3.png',
]
?>

<div class="la-form-group">
    <div class="la-content-option">

        <?php foreach ($dataTypes as $type => $image):?>
            <div class="item <?php echo $type == $value ? 'active' : ''?>" data-target="#content_type" data-selector="true" data-value="<?php echo $type?>" data-type="<?php echo $type?>">
                <div class="inner">
                    <img src="<?php echo $image ?>" alt="" class="la-pic">
                </div>
            </div>
        <?php endforeach;?>

        <input type="hidden" id="content_type" name="Lesson[content_type]" value="<?php echo $value?>" />
    </div>
</div>
<?php foreach ($dataTypes as $type => $image):?>
    <!-- Add content video format -->
    <div class="la-lesson-content la-lesson-<?php echo $type?>  <?php echo $type == $value ? ' active ' : 'hide'?>" data-type="<?php echo $type?>">
        <?php echo $helpers->form->getField($type)?>
    </div>

<?php endforeach;?>
