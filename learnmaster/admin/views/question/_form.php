<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 *
 * @var \lema\helpers\Helper $helper
 */
 ?>

    <div class="la-form-group">
        <label class="lb-text">New question*</label>
        <textarea name="post_content" class="tinymce-st-1 la-form-control" rows="6"></textarea>
    </div>
    <div class="form-question">
        <label class="lb-text">Answers:* <span>Write up to 15 possible answers and indicate which one is the best.</span>
        </label>
        <div class="answers-option">
            <div id="answers-container">
                <?php
                $fields = lema()->helpers->form->generateFormElement($model, $form);
                foreach ($fields as $field):
                    ?>
                    <?php echo $field?>
                <?php endforeach;?>
            </div>
            <div class="add-more-option">
                <button type="button" class="button-secondary flat button button-add-more add-answer-item">Add more option</button>
            </div>
        </div>
        <div class="related-lesson">
            <label class="lb-text">Related Lecture * <span>Select a related video lecture to help students answer this question.</span>
            </label>

            <?php if (!empty($lessons)) :?>
                <select name="Question[related_lesson]" class="la-form-control">
                    <?php foreach ($lessons as $id => $title):?>
                        <option value="<?php echo $id?>" <?php echo $model->related_lesson == $id ? ' selected ' : ''?> ><?php echo $title?></option>
                    <?php endforeach;?>
                </select>
            <?php endif;?>
        </div>

    </div>
