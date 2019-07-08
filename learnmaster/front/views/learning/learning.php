<?php 
  /** * @copyright © 2017 by Solazu Co.,LTD * @project Learn Master Plugin * * @since 1.0 * */ 
?>
<input type="hidden" name="lema-courseId" value="<?php echo $course->post->ID ?>">
<div class="lema-curriculum-page article-lesson lema-navbar-open">
    <div class="lema-curriculum-navbar">
      <div class="lema-curriculum-sidebar-control-st">
          <button type="button" class="lema-btn btn-sidebar"></button>
          <!-- <div class="lema-popover">
              <span class="lema-btn btn-close">&times;</span>
              <div class="inner">
                  <span>Resource available</span>
              </div>
          </div> -->
      </div>
      <div class="lema-topbar">
          <a class="lema-link" href="<?php echo get_permalink($course->ID)?>">
            <i class="fa fa-angle-left"></i> Back to Dashboard
          </a>
      </div>
      <div class="lema-curriculum-content">
        <div class="lema-curriculum-list">
          <?php foreach($chapters as $key=>$chapter): ?>
          <div class="lema-curriculum-chapter <?php if($chapter->ID == $chapter_active) echo 'lema-collapsed'; ?>">
              <div class="lema-chapter-heading">
                  <div class="top">
                      <h2 class="lema-chapter-title"><?php echo $chapter->post_title?></h2>
                      <div class="lema-total-lesson-num">
                          <span class="curr"><?php echo $chapter->total_progress?></span> /
                          <span class="total"><?php echo count($chapter->lessons)?></span>
                      </div>
                  </div>
                  <div class="lm-main">
                      <div class="lema-chapter-desc"><?php echo $chapter->achievement?></div>
                      <?php if(count($chapter->lessons)){ ?>
                      <button type="button" class="lema-btn lema-collapse-btn"></button>
                      <?php } ?>
                  </div>
              </div>
              <?php if(count($chapter->lessons)){ ?>
              <div class="lema-curriculum-body">
                <!-- list lessons -->
                <?php foreach($chapter->lessons as $lesson): 
                  $active = ( isset($learning_progress[$lesson->ID]) && $learning_progress[$lesson->ID] == '1' ) ? 'active' : '';
                    $active .= ($lesson->content_type == 'quiz') ? ' disabled' : '';
                  ?>
                  <div class="lema-curriculum-lesson <?php echo $lesson->content_type?> <?php if($lesson->ID == $lesson_active) echo 'learning'; ?>">
                    <button class="lema-icon-check <?php echo $active?>" data-post_id="<?php echo $lesson->ID?>"></button>
                    <div class="lema-lesson-heading" ajax_content="<?php echo $lesson->ID?>">
                        <h4 class="lema-lesson-title"><?php echo $lesson->post_title?></h4>
                        <?php if($lesson->content_type == 'video'){ 
                          $video = @json_decode($lesson->video,true);
                          if(!empty($video)){
                        ?>
                        <span class="duration"><?php echo $video['fileLength']?></span>
                        <?php }}?>
                    </div>
                    <?php if(!empty($lesson->resourceFiles) && $lesson->resourceFiles[0]['url'] !== ''){ ?>
                    <div class="lema-resource-list">
                      <?php foreach($lesson->resourceFiles as $file){ if($file['url'] !== ''){ ?>
                      <div class="resource-item">
                          <a target="_blank" href="<?php echo $file['url']?>" class="lema-link"><?php echo $file['filename']?></a>
                      </div>
                      <?php }} ?>
                    </div>
                    <?php } ?>
                  </div>
                  <?php endforeach; ?>

              </div>
              <?php } ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div id="ajax_content" class="lema-lecture-view">
      <!-- content -->
      <?php
        if(isset($_GET['lesson'])){
          foreach($chapters as $keyChapter=>$chapter){
            foreach($chapter->lessons as $keyLesson=>$lesson){
              if($lesson->ID == $_GET['lesson']){
                $_POST['id'] = $lesson->ID;
                echo $context->getContent(true);
              }
            }
          }
        }
      ?>
      <!-- end content -->
    </div>
    <div class="lema-article-controls-bar">
      <div class="main-bar">
          <!-- <div class="bar-left">
              <a href="#" class="btn-link"><i class="icon"></i> Discuss in forum</a>
          </div> -->
          <div class="bar-right">
            <button type="button" id="btn-next-chap" class="lema-btn next-chap"><i class="fa fa-step-forward"></i> Next lesson</button>
            <!-- <div class="lema-btn-settings-st">
                <button type="button" class="lema-btn"><i class="fa-cog fa"></i></button>
            </div> -->
            <button type="button" id="btn_fullscreen" class="lema-btn"><i class="fa-arrows-alt fa"></i></button>
          </div>
      </div>
    </div>
</div>
<?php ?>