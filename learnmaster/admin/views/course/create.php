<?php
/**
 * @var \lema\core\components\Form $form
 * @var \lema\models\CourseModel $model
 * @var \lema\models\CourseModel $post
 * @var \lema\admin\controllers\CourseController $context
 */
    //$listChapters = lema\models\CourseModel::getPosts($post->ID,'chapter');
    $fields = lema()->helpers->form->generateFormElement($model, $form);
    $listTab = $model->getChildTab();
    $allTabs = [];
    foreach ($listTab as $keyTab => $arr) {
        $allTabs = array_merge($allTabs, $arr);
    }
?>
<div class="la-course" id="post_<?php echo $post->ID; ?>">
    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <div class="clear"></div>
                <div class="la-tabs course-main-tabs">
                    <ul class="la-nav-tabs">
                        <!--<li class="tab-link current" data-tab="tab-general">Course Information</li>
                        <li class="tab-link" data-tab="tab-curriculum">Curriculum</li>-->
                        <?php foreach ($tabs as $id => $label) :?>
                            <li class="tab-link <?php echo $id=='general' ? 'current' : ''?>" data-tab="tab-<?php echo $id?>"><?php echo $label?></li>
                        <?php endforeach;?>
                    </ul>
                    <div class="la-tabs-content">
                        <div id="tab-general" class="current la-tab-panel">
                            <?php
                            foreach ($fields as $attr => $field) {
                                if (!in_array($attr, $allTabs)) {
                                    echo $field;
                                }
                            }
                            ?>
                            <?php if(apply_filters('lema_course_instructor_form', true)) : ?>
                                <div class="la-form-group">
                                    <label><?php echo __('Instructor', 'lema')?></label>
                                    <select name="instructor[]" multiple="multiple" data-select2-ajax data-action="lema_search_instructor" class="select2 la-form-control">
                                        <?php foreach ($instructors as $id => $name):?>
                                            <option value="<?php echo $id?>" selected="selected"><?php echo $name?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            <?php endif;?>
                            <?php
                            //generate tabs
                            $html_tab = '';
                            $html_tab_content = '';
                            $format_tab = '<li data-tab="%1$s">%2$s</li>';
                            $format_tab_content = '<div class="lema-tab-content %1$s">%2$s</div>';

                            foreach ($listTab as $keyTab => $arr) {
                                $html_tab .= sprintf($format_tab, $keyTab, strtoupper($keyTab)); 
                                $html_field = '';
                                for ($i=0; $i < count($arr); $i++) {
                                    if (isset($fields[$arr[$i]])) {
                                        $html_field .= $fields[$arr[$i]];
                                    } 
                                }
                                $html_tab_content .= sprintf($format_tab_content, $keyTab, $html_field); 
                            }
                            ?>
                            <div class="lema-tab-vertical">
                                <ul><?php echo $html_tab ?></ul>
                                <div class="lema-tab-detail">
                                    <?php echo $html_tab_content ?>
                                </div>
                            </div>
                        </div>

                        <div id="tab-curriculum" class="la-tab-panel">
                            <!-- curriculum content -->
                            <div class="la-lesson-box-wrapper">
                                <!-- Chapter box -->
                                <div id="list-chapter-<?php echo $post->ID; ?>" data-course_id="<?php echo $post->ID; ?>">
                                <?php lema()->helpers->general->registerPjax('lema-chapter-list','ul','lema_ui-sortable')?>
                                    loading...
                                <?php lema()->helpers->general->endPjax()?>
                                </div>
                                <div class="la-add-chapter-wrapp">
                                    <div class="text-center">
                                        <button type="button" class="button-secondary button la-modal-button button-block button-xlage" data-title="Add new chapter" data-lema_modal="la-modal-chapter" data-action="ajax_chapter_form" data-post_type="chapter" data-parent_id="<?php echo $post->ID; ?>">
                                            <span class="fa fa-plus"></span>
                                            <span class="la-text">Add Chapter</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- curriculum content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<div class="clear"></div>
