<?php
/**
 * @var \lema\models\LessonModel $post
 */
?>

<?php if (!empty($post)) :?>
    <div id="lema-audio-player-box" class="lema-audio-wrapper active lema-learning-content">
        <div class="lema-top-heading-bar">
            <h3 class="lema-lesson-title"><?php echo $post->post_title?></h3>
            <div class="lema-index-desc">
                <span>Chapter <?php echo $chapter->post_title?></span>
                <span>Lesson <?php echo $post->menu_order?></span>
            </div>
        </div>
        <?php
        $audio = @json_decode($post->audio,true);
        if(!empty($audio)){
            ?>
            <div class="lema-block-audio" data-target="lema-audio">
            	<img class="lema-audio-image" src="<?php echo $audio['image']['src']?>">
            </div>
            <audio id="lema-audio" class="lema-video-view" controls>
                <source src="<?php echo $audio['url']?>" type="audio/mpeg">

            </audio>
        <?php } ?>
    </div>
<?php else:?>
    <div class="lema-message warning">
        <?php echo __('No audio file found', 'lema')?>
    </div>
<?php endif;?>