<div id="lema-video-player-box" class="lema-video-wrapper active lema-learning-content">
	<div class="lema-top-heading-bar">
	    <h3 class="lema-lesson-title"><?php echo $post->post_title?></h3>
	    <div class="lema-index-desc">
	      <span>Chapter <?php echo $chapter->post_title?></span>
	      <span>Lesson <?php echo $post->menu_order?></span>
	    </div>
	</div>
<?php 
	$video = @json_decode($post->video,true);
  	if(!empty($video)){
?>
<video id="lema-video" class="video-js vjs-default-skin lema-video-view">
	<source src="<?php echo $video['url']?>" type="video/mp4">
	<source src="<?php echo $video['url']?>" type="video/webm">
	<!-- <track kind="captions" src="http://wp.solazu.net/learnmaster/wp-content/plugins/learnmaster/front/assets/libs/videojs/examples/example-captions.vtt" srclang="en" label="English" default></track>
    <track kind="captions" src="http://wp.solazu.net/learnmaster/wp-content/plugins/learnmaster/front/assets/libs/videojs/examples/example-captions.vtt" srclang="ja" label="Japanese"></track> -->
</video>
<?php } ?>
</div>