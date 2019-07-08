<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 * @var \lema\helpers\form\CustomElement $control
 */
$items = @$control->params['value'];
?>
<label class="lb-text">Content Video</label>
<p>Video is Learn Master's preferred delivery type. At least 60% of your course content should be high resolution video
    (720p or HD) with excellent audio and lighting. Upload your video directly to Learn Master for best quality viewing
    and to make full use of learning tools! Widescreen 16:9 ratio is preferred but 4:3 accepted. All files should be
    .mp4 or .mov and less than 4.0 GiB. Please note that the average video length is within 2-10 minutes, and videos
    above 20 minutes long will not be approved.</p>
<label class="lb-text">Choose Video from library</label>
<div class="la-form-group lema-button-add-video <?php if ( isset( $items ) && ! empty( $items ) && ( is_object( $items ) || is_array( $items ) ) ) {
	echo count( $items ) ? ' lema-hide ' : '';
} ?> ">
    <button type="button" class="button-secondary button flat modal-button add-media-button" data-type="video"
            data-callback="updateVideoList" data-multiple="false" data-modal="modal-id-03"><span
                class="fa fa-plus"></span> Add from library
    </button>
</div>
<?php if ( ! empty( $item ) ): ?>
    <input type="hidden" id="la-content-video-resource-value" name="Lesson[video]"
           value='<?php echo( json_encode( $item ) ) ?>'>
<?php endif; ?>

<div id="videos-container">
	<?php
	if ( ! empty( $items ) ) {
		$item = json_decode( $items, true );
		?>
        <div data-type="video" class="la-content-video-resource" id="<?php echo $item['id'] ?>">
            <div class="inner">
                <div class="video-thumb">
                    <video width="200" height="200">
                        <source src="<?php echo $item['url'] ?>" type="<?php echo $item['mime'] ?>">
                    </video>
                </div>
                <div class="video-desc">
                    <h4 class="file-name"><?php echo $item['filename'] ?></h4>
                    <span class="length"><b>Length:</b> 
                        <span class="videoLength"><?php echo $item['fileLength'] ?></span>
                    </span>
                </div>
                <span class="btn-remove remove-video"></span>
            </div>
        </div>
		<?php
	}
	?>
</div>


