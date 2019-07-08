<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
$attrs = [];
if (!empty($data['attributes'])) {
    $attrs = explode(',', $data['attributes']);
}
?>
<?php if(!empty($fields)):?>
    <p class="customfield-title">
        <?php echo $data['title']?>
    </p>
    <ul class="course-custom-fields">
        <?php foreach ($fields as $field): ?>
            <?php
            $name = $field['name'];
            $value = $course->$name;
            if (!empty($attrs) && !in_array($field['name'], $attrs)) {
                continue;
            }
            ?>
            <li>
                <div class="course-customfield field-title"><?php echo $field['label']?></div>
                <div class="course-customfield field-value">
                    <?php if (is_array($value)):?>
                        <?php foreach ($value as $v):?>
                            <div class="field-value-item">
                                <?php echo $v?>
                            </div>
                        <?php endforeach;?>
                    <?php else:?>
                        <?php echo $value?>
                    <?php endif;?>
                </div>
            </li>
        <?php endforeach; ?>

    </ul>

<?php endif;?>
