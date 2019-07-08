<div class="lema-article-wrapper lema-learning-content active">
  <div class="lema-top-heading-bar">
    <h3 class="lema-lesson-title"><?php echo $post->post_title?></h3>
    <div class="lema-index-desc">
      <span>Chapter <?php echo $chapter->post_title?></span>
      <span>Lesson <?php echo $post->menu_order?></span>
    </div>
  </div>
  <div class="article-content entry-content">
        <p><?php echo $post->description?></p>
        <div class="lema-resource-list">

        	<?php $postModel = \lema\models\LessonModel::findOne($post);
        	$resourceFiles = $postModel->getResourceFiles();
        	foreach($resourceFiles as $file){ if($file['url'] !== ''){ ?>
          <div class="resource-item">
             <a href="<?php echo $file['url']?>" class="lema-link"><?php echo $file['filename']?></a>
          </div>
          <?php }} ?>
        </div>
        <?php echo $post->article?>
  </div>
</div>
