<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
?>
<?php if (!empty($lessons)):?>
    <?php $i = 0;?>
    <?php foreach ($lessons as $lesson):?>
        <?php $i++; $lessonModel = $context->getLessonModel($lesson)?>
        <li data-id="<?php echo $lesson->ID?>" class="la-lesson-box postbox <?php echo ($current && $current == $lesson->ID) ? ' open ' : ''?>">
            <div class="la-lesson-bar hndle">
                <div class="la-lesson-group">
                    <span class="la-chapter-lb">Lesson</span>
                    <span class="la-chap-num"> #<?php echo $i;?></span>
                    <span class="la-lesson-title" data-type="<?php echo $lessonModel->content_type?>"><?php echo $lesson->post_title; ?></span>
                    <div class="edit-group">
                        <div class="inner">
                    <span class="la-modal-button" data-edit_action="ajax_lesson_form"  data-post_type="lesson"  data-post_parent="<?php echo $chapterId?>" data-post_id="<?php echo $lesson->ID; ?>"  data-title="Edit lesson" data-lema_modal="la-modal-lesson" title="Edit Lesson title">
                        <i class="fa fa-edit"></i>
                    </span>
                            <span data-action="ajax_delete_lesson"  data-target="lesson-list" data-chapter_id="<?php echo $chapterId; ?>" data-post_id="<?php echo $lesson->ID; ?>"  data-nonce="<?php echo wp_create_nonce('lema_nonce') ?>" class="modal-button la-button-remove la-delete-lesson" title="Remove"></span>
                        </div>
                    </div>
                </div>
                <span class="la-button-collapse" title="Collapse"></span>
            </div>
            <div class="inside">
                <div class="la-lesson-detail">
                    <div data-type="lesson" class="la-block-detail article-content active">
                        <div class="la-element-icon">
                            <span class="la-icon"></span>
                        </div>
                        <div class="la-element-title">
                            <p>
                                <?php echo $lesson->post_content?>
                            </p>
                        </div>

                    </div>
                    <div class="la-lesson-resource-list">
                        <div class="text-left la-action-bar la-clear">
                            <div class="lb-action-title">Resources list</div>
                            <button type="button" class="button-secondary button flat modal-button" data-edit_action="ajax_lesson_form"  data-post_type="lesson"  data-post_parent="<?php echo $chapterId?>" data-tab="resource" data-post_id="<?php echo $lesson->ID; ?>"  data-title="Edit lesson" data-lema_modal="la-modal-lesson" title="Edit Lesson title" data-tabcontent="tab-14"><span class="fa fa-pencil"></span> Manage resources</button>
                        </div>
                        <?php
                        $files = $lessonModel->getResourceFiles();
                        if (!empty($files)):
                        ?>
                        <ul class="la-resource-list ui-sortable">
                            <?php
                            foreach ($files as $file) :
                            ?>
                                <?php if (empty($file['url'])) continue;?>
                                <li class="item" data-type="article">
                                    <div class="main-resource-title hndle ui-sortable-handle">
                                        <span><a href="<?php echo $file['url']?>" target="_blank"><?php echo $file['filename']?></a> </span>
                                    </div>
                                </li>
                            <?php endforeach;?>
                        </ul>
                        <?php endif;?>
                    </div>
                </div>


            </div>
        </li>
    <?php endforeach;?>    

<?php else:?>
    <div class="alert">No lesson found</div>
<?php endif;?>
