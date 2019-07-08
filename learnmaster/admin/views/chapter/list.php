<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 * @var \lema\helpers\Helper $helpers
 * @var \lema\admin\controllers\ChapterController $context
 */
//$listPostOption = ['lesson'=>[],'quiz'=>[]];
//foreach($listPostOption as $key=>$postOption){
//    $listPostOption[$key] = lema\models\CourseModel::getPosts($post->ID,$key);
//}
 ?>
<?php if (isset($chapters)):?>
    <?php $i = 0;?>
    <?php foreach ($chapters as $chapter):?>
        <?php $i++;?>
        <li data-id="<?php echo $chapter->ID?>" class="la-chapter-box <?php echo ($current && $current == $chapter->ID) ? ' open ' : ''?>">
            <div class="la-chapter-bar hndle">
                <div class="la-chapter-group">
                    <span class="la-chapter-lb">Chapter</span>
                    <span class="la-chap-num">#<?php echo $i?></span>
                    <span class="la-chapter-title" data-type="article"><?php echo $chapter->post_title; ?></span>
                    <div class="edit-group">
                        <div class="inner">
                            <span class="lema-edit-button" data-lema_modal="la-modal-chapter" data-edit_action="ajax_chapter_form"  data-title="Edit chapter" data-post_type="<?php echo $chapter->post_type; ?>" data-post_id="<?php echo $chapter->ID; ?>" data-post_parent="<?php echo $chapter->post_parent; ?>" title="Edit chater title">
                                <i class="fa fa-pencil"></i>
                            </span>
                           <!--  <span class="la-button-duplicate" title="Duplicate Chapter" data-action="ajax_duplicate_post" data-post_id="<?php echo $chapter->ID; ?>" ></span> -->
                            <span data-action="ajax_delete_chapter" data-target="chapter-list" data-post_id="<?php echo $chapter->ID; ?>" data-nonce="<?php echo wp_create_nonce('lema_nonce') ?>" class="modal-button la-button-remove la-delete-chapter" title="Remove"></span>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
                <span class="la-button-collapse" title="Collapse"></span>
            </div>
            <div class="inside ">
                <ul id="lema-data-list-<?php echo $chapter->ID?>" class="lema_ui-sortable">
                    <?php echo $context->renderDataList($chapter->ID, false)?>
                </ul>

                <div class="la-btn-create-lesson">
                    <div class="row text-center">
                        <div class="la-col-50">
                            <button type="button"  data-title="Add new lesson"  data-action="ajax_lesson_form"  data-lema_modal="la-modal-lesson" data-post_type="lesson" data-post_parent="<?php echo $chapter->ID?>" class="button-secondary button button-block button-xlage flat la-modal-button">
                                <span class="fa fa-plus"></span>
                                <span class="la-text">Add lesson</span>
                            </button>
                        </div>
                        <div class="la-col-50">
                            <button type="button" data-action="ajax_quiz_form"  data-title="Add new quiz" data-lema_modal="la-modal-quiz" data-post_type="quiz" data-post_parent="<?php echo $chapter->ID?>" class="button-secondary button la-modal-button button-block flat button-xlage">
                                <span class="fa fa-plus"></span>
                                <span class="la-text">Add Quiz</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    <?php endforeach;?>
<?php endif;?>

