<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
$i = 0;
?>
<?php foreach ($questions as $quiz) :?>
    <?php $i++;?>
    <li data-id="<?php echo $quiz->ID?>" id="quiz_<?php echo $quiz->ID; ?>" class="la-quiz-box postbox <?php echo ($current && $current == $quiz->ID) ? ' open ' : ''?>">
        <div class="la-quiz-bar hndle">
            <div class="la-quiz-group">
                <span class="la-chapter-lb">Quiz</span>
                <span class="la-chap-num">#<?php echo $i?></span>
                <span class="la-quiz-title" data-type="quiz"><?php echo $quiz->post_title?></span>
            </div>
            <div class="edit-group">
                <div class="inner">
                    <span class="la-modal-button la-button-edit" data-edit_action="ajax_quiz_form" data-post_parent="<?php echo $chapterId?>" data-post_type="quiz" data-post_id="<?php echo $quiz->ID; ?>"  data-title="Edit quiz" data-lema_modal="la-modal-quiz" title="Edit Quiz title"></span>
                    <span data-action="ajax_delete_quiz" data-target="data-list" data-chapter_id="<?php echo $chapterId?>" data-post_id="<?php echo $quiz->ID; ?>" data-nonce="<?php echo wp_create_nonce('lema_nonce') ?>" class="modal-button la-button-remove" title="Remove"></span>
                </div>
            </div>
            <span class="la-button-collapse" title="Collapse"></span>
        </div>
        <div class="inside">
            <div class="question-list-wrapper">
                <div class="top-bar la-action-bar la-clear">
                    <h4 class="lb-action-title">Questions List</h4>
                    <span class="button button-secondary flat modal-button" data-action="ajax_question_form" data-post_type="question"  data-title="Add new question" data-lema_modal="la-modal-question" data-post_parent="<?php echo $quiz->ID; ?>"><i class="fa fa-plus"></i> New question</span>
                </div>
                <div id="lema-question-list-<?php echo $quiz->ID?>">
                    <?php echo $context->renderQuestionList($quiz)?>
                </div>


            </div>
        </div>
    </li>

<?php endforeach;?>

