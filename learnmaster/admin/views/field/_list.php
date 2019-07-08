<?php
/**
 * @project  eduall
 * @copyright © 2017 by Chuke Hill Co.,LTD
 * @author ivoglent
 * @time  11/13/17.
 */
?>
<h4><?php echo __('Current fields', 'lema')?></h4>
<table class="wp-list-table widefat fixed striped tags">
    <thead>
    <tr>
        <th scope="col" id="name" class="manage-column column-slug"><span>Name</span><span class="sorting-indicator"></span></th>
        <th scope="col" id="label" class="manage-column column-name column-primary"><span>Label</span><span class="sorting-indicator"></span></th>
        <th scope="col" id="type" class="manage-column column-description"><span>Type</span><span class="sorting-indicator"></span></th>
        <th scope="col" id="default" class="manage-column column-posts num"><span>Default</span><span class="sorting-indicator"></span></th>
    </tr>
    </thead>

    <tbody id="the-list" data-wp-lists="list:tag">
    <?php foreach ($fields as $name => $field) :?>
        <?php
        switch ($field['type']) {
            case 'list' :
            case 'select' :
            case 'radiolist' :
            case 'checklist' :
                $field['default'] = implode("<br />", $field['default']);
                break;
        }
        ?>
        <tr>
            <td class="colspanchange">
                <a href="edit.php?post_type=course&page=course-custom-fields&name=<?php echo $field['name']?>"><?php echo $field['name']?></td>
            </td>
            <td class="colspanchange"><?php echo $field['label']?></td>
            <td class="colspanchange"><?php echo $field['type']?></td>
            <td class="colspanchange"><?php echo $field['default']?></td>
        </tr>
    <?php endforeach;?>
    </tbody>



</table>