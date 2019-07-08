<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
?>
<?php if (count($questions)):?>
<ul class="la-question-list ui-sortable lema_ui-sortable">
    <?php foreach ($questions as $question):?>
        <li data-id="<?php echo $question->ID?>">
            <div class="main-question-title hndle">
                <span><?php echo strip_tags($question->post_content)?></span>
                <div class="la-btn-group">
                   <!-- <span class="la-btn-edit modal-button" data-modal="modal-id-05"></span>-->
                    <span data-target="question-list" data-post_id="<?php echo $question->ID?>"  data-quiz_id="<?php echo $quizId?>" data-action="ajax_delete_question" class="la-button-remove"></span>
                </div>
            </div>
        </li>
    <?php endforeach;?>

</ul>
<?php else:?>
    <p>
        <?php echo __('No question selected', 'lema')?>
    </p>
<?php endif;?>
