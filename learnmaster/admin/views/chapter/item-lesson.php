<?php $dataModel = \lema\models\LessonModel::findOne($data); ?>
<li data-id="<?php echo $data->ID?>" class="la-lesson-box postbox <?php echo ($current && $current == $data->ID) ? ' open ' : ''?>">
    <div class="la-lesson-bar hndle">
        <div class="la-lesson-group">
            <span class="la-chapter-lb">Lesson</span>
            <span class="la-chap-num"> #<?php echo $i;?></span>
            <span class="la-lesson-title" data-type="<?php echo $dataModel->content_type?>"><?php echo $data->post_title; ?></span>
            <div class="edit-group">
                <div class="inner">
            <span class="la-modal-button" data-edit_action="ajax_lesson_form"  data-post_type="lesson"  data-post_parent="<?php echo $chapterId?>" data-post_id="<?php echo $data->ID; ?>"  data-title="Edit lesson" data-lema_modal="la-modal-lesson" title="Edit Lesson title">
                <i class="fa fa-edit"></i>
            </span>
                    <span data-action="ajax_delete_lesson"  data-target="data-list" data-chapter_id="<?php echo $chapterId; ?>" data-post_id="<?php echo $data->ID; ?>"  data-nonce="<?php echo wp_create_nonce('lema_nonce') ?>" class="modal-button la-button-remove la-delete-lesson" title="Remove"></span>
                </div>
            </div>
        </div>
        <span class="la-button-collapse" title="Collapse"></span>
    </div>
    <div class="inside">
        <div class="la-lesson-detail">
            <div data-type="lesson" class="la-block-detail <?php echo $data->content_type?>-content active">
                <div class="la-element-icon">
                    <span class="la-icon"></span>
                </div>
                <div class="la-element-title">
                    <?php switch ($data->content_type) {
                        case 'video':
                        case 'audio':
                            $content_type = $data->content_type;
                            $data_content = json_decode($dataModel->$content_type);
                            ?>
                            <a target="_blank" href="<?= !empty($data_content->url)?$data_content->url:''?>"><?= !empty($data_content->title)?$data_content->title:''?></a>
                            <p>Time: <?= !empty($data_content->fileLength)?$data_content->fileLength:''?></p>
                            <?php
                            break;

                            break;
                        default:
                            echo $data->post_content;
                            break;
                    }
                    ?>
                </div>
            </div>
            <?php if($data->content_type !== 'article'){ ?>
            <div class="la-element-des">
                <?php echo $data->post_content; ?>
            </div>
            <?php } ?>
            <div class="la-lesson-resource-list">
                <div class="text-left la-action-bar la-clear">
                    <div class="lb-action-title">Resources list</div>
                    <button data-tab="tab-14" type="button" class="button-secondary button flat modal-button" data-edit_action="ajax_lesson_form"  data-post_type="lesson"  data-post_parent="<?php echo $chapterId?>" data-tab="resource" data-post_id="<?php echo $data->ID; ?>"  data-title="Edit lesson" data-lema_modal="la-modal-lesson" title="Edit Lesson title" data-tabcontent="tab-14"><span class="fa fa-pencil"></span> Manage resources</button>
                </div>
                <?php
                $files = $dataModel->getResourceFiles();
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